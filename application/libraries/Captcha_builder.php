<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Smarter Dog - Open Source Web Scheduler
 *
 * @package     SmarterDog
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) 2013 - 2020, Alex Tselegidis
 * @license     http://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        http://easyappointments.org
 * @since       v1.4.3
 * ---------------------------------------------------------------------------- */

use Gregwar\Captcha\PhraseBuilder;
use Gregwar\Captcha\PhraseBuilderInterface;

/**
 * Class Captcha_builder
 *
 * This class replaces the Gregwar\Captcha\CaptchaBuilder so that it becomes PHP 8.1 compatible.
 */
class Captcha_builder
{
    /**
     * Temporary dir, for OCR check
     *
     * @var string
     */
    public string $tempDir = 'temp/';
    /**
     * @var array<int, int>
     */
    protected array $fingerprint = [];
    /**
     * @var bool
     */
    protected bool $useFingerprint = false;
    /**
     * @var array<int, int>
     */
    protected array $textColor = [];
    /**
     * @var array<int, int>|null
     */
    protected ?array $lineColor = null;
    /**
     * @var int|false|null
     */
    protected int|false|null $background = null;
    /**
     * @var array<int, int>|null
     */
    protected ?array $backgroundColor = null;
    /**
     * @var array<int, string>
     */
    protected array $backgroundImages = [];
    /**
     * @var \GdImage|null
     */
    protected ?\GdImage $contents = null;
    /**
     * @var string|null
     */
    protected ?string $phrase = null;
    /**
     * @var PhraseBuilderInterface
     */
    protected PhraseBuilderInterface $builder;
    /**
     * @var bool
     */
    protected bool $distortion = true;
    /**
     * The maximum number of lines to draw in front of
     * the image. null - use default algorithm
     *
     * @var int|null
     */
    protected ?int $maxFrontLines = null;
    /**
     * The maximum number of lines to draw behind
     * the image. null - use default algorithm
     *
     * @var int|null
     */
    protected ?int $maxBehindLines = null;
    /**
     * The maximum angle of char
     *
     * @var int
     */
    protected int $maxAngle = 8;
    /**
     * The maximum offset of char
     *
     * @var int
     */
    protected int $maxOffset = 5;
    /**
     * Is the interpolation enabled ?
     *
     * @var bool
     */
    protected bool $interpolation = true;
    /**
     * Ignore all effects
     *
     * @var bool
     */
    protected bool $ignoreAllEffects = false;
    /**
     * Allowed image types for the background images
     *
     * @var array<int, string>
     */
    protected array $allowedBackgroundImageTypes = ['image/png', 'image/jpeg', 'image/gif'];

    /**
     * Captcha_builder constructor.
     *
     * @param string|null $phrase The phrase to use, or null to generate one.
     * @param PhraseBuilderInterface|null $builder The phrase builder instance.
     */
    public function __construct(?string $phrase = null, ?PhraseBuilderInterface $builder = null)
    {
        if ($builder === null) {
            $this->builder = new PhraseBuilder();
        } else {
            $this->builder = $builder;
        }

        $this->phrase = is_string($phrase) ? $phrase : $this->builder->build($phrase);
    }

    /**
     * Generate the image
     *
     * @param int $width Image width in pixels.
     * @param int $height Image height in pixels.
     * @param string|null $font Font file path.
     * @param array<int, int>|null $fingerprint Fingerprint array for reproducible builds.
     *
     * @return self
     *
     * @throws Exception
     */
    public function build(int $width = 150, int $height = 40, ?string $font = null, ?array $fingerprint = null): self
    {
        if (null !== $fingerprint) {
            $this->fingerprint = $fingerprint;
            $this->useFingerprint = true;
        } else {
            $this->fingerprint = [];
            $this->useFingerprint = false;
        }

        if ($font === null) {
            $font =
                __DIR__ . '/../../vendor/gregwar/captcha/src/Gregwar/Captcha/Font/captcha' . $this->rand(0, 5) . '.ttf';
        }

        $bg = null;

        if (empty($this->backgroundImages)) {
            // if background images list is not set, use a color fill as a background
            $image = imagecreatetruecolor($width, $height);
            if ($this->backgroundColor == null) {
                $bg = imagecolorallocate($image, $this->rand(200, 255), $this->rand(200, 255), $this->rand(200, 255));
            } else {
                $color = $this->backgroundColor;
                $bg = imagecolorallocate($image, $color[0], $color[1], $color[2]);
            }
            $this->background = $bg;
            imagefill($image, 0, 0, $bg);
        } else {
            // use a random background image
            $randomBackgroundImage = $this->backgroundImages[rand(0, count($this->backgroundImages) - 1)];

            $imageType = $this->validateBackgroundImage($randomBackgroundImage);

            $image = $this->createBackgroundImageFromType($randomBackgroundImage, $imageType);
        }

        // Apply effects
        if (!$this->ignoreAllEffects) {
            $square = $width * $height;
            $effects = $this->rand($square / 3000, $square / 2000);

            // set the maximum number of lines to draw in front of the text
            if ($this->maxBehindLines != null && $this->maxBehindLines > 0) {
                $effects = min($this->maxBehindLines, $effects);
            }

            if ($this->maxBehindLines !== 0) {
                for ($e = 0; $e < $effects; $e++) {
                    $this->drawLine($image, $width, $height);
                }
            }
        }

        // Write CAPTCHA text
        $color = $this->writePhrase($image, $this->phrase, $font, $width, $height);

        // Apply effects
        if (!$this->ignoreAllEffects) {
            $square = $width * $height;
            $effects = $this->rand($square / 3000, $square / 2000);

            // set the maximum number of lines to draw in front of the text
            if ($this->maxFrontLines != null && $this->maxFrontLines > 0) {
                $effects = min($this->maxFrontLines, $effects);
            }

            if ($this->maxFrontLines !== 0) {
                for ($e = 0; $e < $effects; $e++) {
                    $this->drawLine($image, $width, $height, $color);
                }
            }
        }

        // Distort the image
        if ($this->distortion && !$this->ignoreAllEffects) {
            $image = $this->distort($image, $width, $height, $bg);
        }

        // Post effects
        if (!$this->ignoreAllEffects) {
            $this->postEffect($image);
        }

        $this->contents = $image;

        return $this;
    }

    /**
     * Returns a random number or the next number in the
     * fingerprint
     *
     * @param int|float $min Minimum value.
     * @param int|float $max Maximum value.
     *
     * @return int
     */
    protected function rand(int|float $min, int|float $max): int
    {
        if (!is_array($this->fingerprint)) {
            $this->fingerprint = [];
        }

        if ($this->useFingerprint) {
            $value = current($this->fingerprint);
            next($this->fingerprint);
        } else {
            $value = mt_rand((int) $min, (int) $max);
            $this->fingerprint[] = $value;
        }

        return $value;
    }

    /**
     * Validate the background image path. Return the image type if valid
     *
     * @param string $backgroundImage
     * @return string
     * @throws Exception
     */
    protected function validateBackgroundImage(string $backgroundImage): string
    {
        // check if file exists
        if (!file_exists($backgroundImage)) {
            $backgroundImageExploded = explode('/', $backgroundImage);
            $imageFileName =
                count($backgroundImageExploded) > 1
                    ? $backgroundImageExploded[count($backgroundImageExploded) - 1]
                    : $backgroundImage;

            throw new Exception('Invalid background image: ' . $imageFileName);
        }

        // check image type
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
        $imageType = finfo_file($finfo, $backgroundImage);
        finfo_close($finfo);

        if (!in_array($imageType, $this->allowedBackgroundImageTypes)) {
            throw new Exception(
                'Invalid background image type! Allowed types are: ' . join(', ', $this->allowedBackgroundImageTypes),
            );
        }

        return $imageType;
    }

    /**
     * Create background image from type
     *
     * @param string $backgroundImage
     * @param string $imageType
     * @return \GdImage|false
     * @throws Exception
     */
    protected function createBackgroundImageFromType(string $backgroundImage, string $imageType): \GdImage|false
    {
        switch ($imageType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($backgroundImage);
                break;
            case 'image/png':
                $image = imagecreatefrompng($backgroundImage);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($backgroundImage);
                break;

            default:
                throw new Exception('Not supported file type for background image!');
        }

        return $image;
    }

    /**
     * Draw lines over the image
     *
     * @param \GdImage $image The GD image resource.
     * @param int $width Image width.
     * @param int $height Image height.
     * @param int|null $tcol Line color identifier.
     *
     * @return void
     */
    protected function drawLine(\GdImage $image, int $width, int $height, ?int $tcol = null): void
    {
        if ($this->lineColor === null) {
            $red = $this->rand(100, 255);
            $green = $this->rand(100, 255);
            $blue = $this->rand(100, 255);
        } else {
            $red = $this->lineColor[0];
            $green = $this->lineColor[1];
            $blue = $this->lineColor[2];
        }

        if ($tcol === null) {
            $tcol = imagecolorallocate($image, $red, $green, $blue);
        }

        if ($this->rand(0, 1)) {
            // Horizontal
            $Xa = $this->rand(0, $width / 2);
            $Ya = $this->rand(0, $height);
            $Xb = $this->rand($width / 2, $width);
            $Yb = $this->rand(0, $height);
        } else {
            // Vertical
            $Xa = $this->rand(0, $width);
            $Ya = $this->rand(0, $height / 2);
            $Xb = $this->rand(0, $width);
            $Yb = $this->rand($height / 2, $height);
        }
        imagesetthickness($image, $this->rand(1, 3));
        imageline($image, $Xa, $Ya, $Xb, $Yb, $tcol);
    }

    /**
     * Writes the phrase on the image
     *
     * @param \GdImage $image The GD image resource.
     * @param string|null $phrase The phrase to write.
     * @param string $font The font file path.
     * @param int $width Image width.
     * @param int $height Image height.
     *
     * @return int|false Returns the color identifier.
     */
    protected function writePhrase(\GdImage $image, ?string $phrase, string $font, int $width, int $height): int|false
    {
        $length = mb_strlen($phrase);
        if ($length === 0) {
            return imagecolorallocate($image, 0, 0, 0);
        }

        // Gets the text size and start position
        $size = (int) round($width / $length) - $this->rand(0, 3) - 1;
        $box = imagettfbbox($size, 0, $font, $phrase);
        $textWidth = $box[2] - $box[0];
        $textHeight = $box[1] - $box[7];
        $x = (int) round(($width - $textWidth) / 2);
        $y = (int) round(($height - $textHeight) / 2) + $size;

        if (!$this->textColor) {
            $textColor = [$this->rand(0, 150), $this->rand(0, 150), $this->rand(0, 150)];
        } else {
            $textColor = $this->textColor;
        }
        $col = imagecolorallocate($image, $textColor[0], $textColor[1], $textColor[2]);

        // Write the letters one by one, with random angle
        for ($i = 0; $i < $length; $i++) {
            $symbol = mb_substr($phrase, $i, 1);
            $box = imagettfbbox($size, 0, $font, $symbol);
            $w = $box[2] - $box[0];
            $angle = $this->rand(-$this->maxAngle, $this->maxAngle);
            $offset = $this->rand(-$this->maxOffset, $this->maxOffset);
            imagettftext($image, $size, $angle, $x, $y + $offset, $col, $font, $symbol);
            $x += $w;
        }

        return $col;
    }

    /**
     * Distorts the image
     *
     * @param \GdImage $image The GD image resource.
     * @param int $width Image width.
     * @param int $height Image height.
     * @param int|false|null $bg Background color identifier.
     *
     * @return \GdImage|false Returns the distorted image.
     */
    public function distort(\GdImage $image, int $width, int $height, int|false|null $bg): \GdImage|false
    {
        $contents = imagecreatetruecolor($width, $height);
        $X = $this->rand(0, $width);
        $Y = $this->rand(0, $height);
        $phase = $this->rand(0, 10);
        $scale = 1.1 + $this->rand(0, 10000) / 30000;
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $Vx = $x - $X;
                $Vy = $y - $Y;
                $Vn = sqrt($Vx * $Vx + $Vy * $Vy);

                if ($Vn != 0) {
                    $Vn2 = $Vn + 4 * sin($Vn / 30);
                    $nX = $X + ($Vx * $Vn2) / $Vn;
                    $nY = $Y + ($Vy * $Vn2) / $Vn;
                } else {
                    $nX = $X;
                    $nY = $Y;
                }
                $nY = $nY + $scale * sin($phase + $nX * 0.2);

                if ($this->interpolation) {
                    $p = $this->interpolate(
                        $nX - floor($nX),
                        $nY - floor($nY),
                        $this->getCol($image, floor($nX), floor($nY), $bg),
                        $this->getCol($image, ceil($nX), floor($nY), $bg),
                        $this->getCol($image, floor($nX), ceil($nY), $bg),
                        $this->getCol($image, ceil($nX), ceil($nY), $bg),
                    );
                } else {
                    $p = $this->getCol($image, round($nX), round($nY), $bg);
                }

                if ($p == 0) {
                    $p = $bg;
                }

                imagesetpixel($contents, $x, $y, $p);
            }
        }

        return $contents;
    }

    /**
     * Interpolate colors for distortion.
     *
     * @param float|int $x X interpolation factor.
     * @param float|int $y Y interpolation factor.
     * @param int|false|null $nw North-west color.
     * @param int|false|null $ne North-east color.
     * @param int|false|null $sw South-west color.
     * @param int|false|null $se South-east color.
     *
     * @return int
     */
    protected function interpolate(float|int $x, float|int $y, int|false|null $nw, int|false|null $ne, int|false|null $sw, int|false|null $se): int
    {
        [$r0, $g0, $b0] = $this->getRGB($nw);
        [$r1, $g1, $b1] = $this->getRGB($ne);
        [$r2, $g2, $b2] = $this->getRGB($sw);
        [$r3, $g3, $b3] = $this->getRGB($se);

        $cx = 1.0 - $x;
        $cy = 1.0 - $y;

        $m0 = $cx * $r0 + $x * $r1;
        $m1 = $cx * $r2 + $x * $r3;
        $r = (int) ($cy * $m0 + $y * $m1);

        $m0 = $cx * $g0 + $x * $g1;
        $m1 = $cx * $g2 + $x * $g3;
        $g = (int) ($cy * $m0 + $y * $m1);

        $m0 = $cx * $b0 + $x * $b1;
        $m1 = $cx * $b2 + $x * $b3;
        $b = (int) ($cy * $m0 + $y * $m1);

        return ($r << 16) | ($g << 8) | $b;
    }

    /**
     * Extract RGB components from a color integer.
     *
     * @param int|false|null $col Color integer.
     *
     * @return array<int, int>
     */
    protected function getRGB(int|false|null $col): array
    {
        return [(int) ($col >> 16) & 0xff, (int) ($col >> 8) & 0xff, (int) $col & 0xff];
    }

    /**
     * Get the color of a pixel in the image.
     *
     * @param \GdImage $image The GD image resource.
     * @param int|float $x X coordinate.
     * @param int|float $y Y coordinate.
     * @param int|false|null $background Background color identifier.
     *
     * @return int|false|null
     */
    protected function getCol(\GdImage $image, int|float $x, int|float $y, int|false|null $background): int|false|null
    {
        $L = imagesx($image);
        $H = imagesy($image);
        if ($x < 0 || $x >= $L || $y < 0 || $y >= $H) {
            return $background;
        }

        return imagecolorat($image, (int) $x, (int) $y);
    }

    /**
     * Apply some post effects
     *
     * @param \GdImage $image The GD image resource.
     *
     * @return void
     */
    protected function postEffect(\GdImage $image): void
    {
        if (!function_exists('imagefilter')) {
            return;
        }

        if ($this->backgroundColor != null || $this->textColor != null) {
            return;
        }

        // Negate ?
        if ($this->rand(0, 1) == 0) {
            imagefilter($image, IMG_FILTER_NEGATE);
        }

        // Edge ?
        if ($this->rand(0, 10) == 0) {
            imagefilter($image, IMG_FILTER_EDGEDETECT);
        }

        // Contrast
        imagefilter($image, IMG_FILTER_CONTRAST, $this->rand(-50, 10));

        // Colorize
        if ($this->rand(0, 5) == 0) {
            imagefilter($image, IMG_FILTER_COLORIZE, $this->rand(-80, 50), $this->rand(-80, 50), $this->rand(-80, 50));
        }
    }

    /**
     * Instantiation
     *
     * @param string|null $phrase The phrase to use, or null to generate one.
     *
     * @return self
     */
    public static function create(?string $phrase = null): self
    {
        return new self($phrase);
    }

    /**
     * The image contents
     *
     * @return \GdImage|null
     */
    public function getContents(): ?\GdImage
    {
        return $this->contents;
    }

    /**
     * Enable/Disables the interpolation
     *
     * @param bool $interpolate True to enable, false to disable.
     *
     * @return self
     */
    public function setInterpolation(bool $interpolate = true): self
    {
        $this->interpolation = $interpolate;

        return $this;
    }

    /**
     * Enables/disable distortion
     *
     * @param bool $distortion Whether to enable distortion.
     *
     * @return self
     */
    public function setDistortion(bool $distortion): self
    {
        $this->distortion = (bool) $distortion;

        return $this;
    }

    /**
     * Set the maximum number of lines to draw behind the text.
     *
     * @param int|null $maxBehindLines Maximum lines behind text.
     *
     * @return self
     */
    public function setMaxBehindLines(?int $maxBehindLines): self
    {
        $this->maxBehindLines = $maxBehindLines;

        return $this;
    }

    /**
     * Set the maximum number of lines to draw in front of the text.
     *
     * @param int|null $maxFrontLines Maximum lines in front of text.
     *
     * @return self
     */
    public function setMaxFrontLines(?int $maxFrontLines): self
    {
        $this->maxFrontLines = $maxFrontLines;

        return $this;
    }

    /**
     * Set the maximum angle for characters.
     *
     * @param int $maxAngle Maximum angle in degrees.
     *
     * @return self
     */
    public function setMaxAngle(int $maxAngle): self
    {
        $this->maxAngle = $maxAngle;

        return $this;
    }

    /**
     * Set the maximum offset for characters.
     *
     * @param int $maxOffset Maximum offset in pixels.
     *
     * @return self
     */
    public function setMaxOffset(int $maxOffset): self
    {
        $this->maxOffset = $maxOffset;

        return $this;
    }

    /**
     * Sets the text color to use
     *
     * @param int $r Red component (0-255).
     * @param int $g Green component (0-255).
     * @param int $b Blue component (0-255).
     *
     * @return self
     */
    public function setTextColor(int $r, int $g, int $b): self
    {
        $this->textColor = [$r, $g, $b];

        return $this;
    }

    /**
     * Sets the background color to use
     *
     * @param int $r Red component (0-255).
     * @param int $g Green component (0-255).
     * @param int $b Blue component (0-255).
     *
     * @return self
     */
    public function setBackgroundColor(int $r, int $g, int $b): self
    {
        $this->backgroundColor = [$r, $g, $b];

        return $this;
    }

    /**
     * Sets the line color to use
     *
     * @param int $r Red component (0-255).
     * @param int $g Green component (0-255).
     * @param int $b Blue component (0-255).
     *
     * @return self
     */
    public function setLineColor(int $r, int $g, int $b): self
    {
        $this->lineColor = [$r, $g, $b];

        return $this;
    }

    /**
     * Sets the ignoreAllEffects value
     *
     * @param bool $ignoreAllEffects
     * @return self
     */
    public function setIgnoreAllEffects(bool $ignoreAllEffects): self
    {
        $this->ignoreAllEffects = $ignoreAllEffects;

        return $this;
    }

    /**
     * Sets the list of background images to use (one image is randomly selected)
     *
     * @param array<int, string> $backgroundImages List of image file paths.
     *
     * @return self
     */
    public function setBackgroundImages(array $backgroundImages): self
    {
        $this->backgroundImages = $backgroundImages;

        return $this;
    }

    /**
     * Builds while the code is readable against an OCR
     *
     * @param int $width Image width in pixels.
     * @param int $height Image height in pixels.
     * @param string|null $font Font file path.
     * @param array<int, int>|null $fingerprint Fingerprint array.
     *
     * @return void
     */
    public function buildAgainstOCR(int $width = 150, int $height = 40, ?string $font = null, ?array $fingerprint = null): void
    {
        do {
            $this->build($width, $height, $font, $fingerprint);
        } while ($this->isOCRReadable());
    }

    /**
     * Try to read the code against an OCR
     *
     * @return bool
     */
    public function isOCRReadable(): bool
    {
        if (!is_dir($this->tempDir)) {
            @mkdir($this->tempDir, 0755, true);
        }

        $tempj = $this->tempDir . uniqid('captcha', true) . '.jpg';
        $tempp = $this->tempDir . uniqid('captcha', true) . '.pgm';

        $this->save($tempj);
        shell_exec("convert $tempj $tempp");
        $value = trim(strtolower(shell_exec("ocrad $tempp")));

        @unlink($tempj);
        @unlink($tempp);

        return $this->testPhrase($value);
    }

    /**
     * Saves the Captcha to a jpeg file
     *
     * @param string $filename Output file path.
     * @param int $quality JPEG quality (0-100).
     *
     * @return void
     */
    public function save(string $filename, int $quality = 90): void
    {
        imagejpeg($this->contents, $filename, $quality);
    }

    /**
     * Returns true if the given phrase is good
     *
     * @param string $phrase The phrase to test.
     *
     * @return bool
     */
    public function testPhrase(string $phrase): bool
    {
        return $this->builder->niceize($phrase) == $this->builder->niceize($this->getPhrase());
    }

    /**
     * Gets the captcha phrase
     *
     * @return string|null
     */
    public function getPhrase(): ?string
    {
        return $this->phrase;
    }

    /**
     * Setting the phrase
     *
     * @param string $phrase The phrase to set.
     *
     * @return void
     */
    public function setPhrase(string $phrase): void
    {
        $this->phrase = (string) $phrase;
    }

    /**
     * Gets the image GD
     *
     * @return \GdImage|null
     */
    public function getGd(): ?\GdImage
    {
        return $this->contents;
    }

    /**
     * Gets the HTML inline base64
     *
     * @param int $quality JPEG quality (0-100).
     *
     * @return string
     */
    public function inline(int $quality = 90): string
    {
        return 'data:image/jpeg;base64,' . base64_encode($this->get($quality));
    }

    /**
     * Gets the image contents
     *
     * @param int $quality JPEG quality (0-100).
     *
     * @return string|false
     */
    public function get(int $quality = 90): string|false
    {
        ob_start();
        $this->output($quality);

        return ob_get_clean();
    }

    /**
     * Outputs the image
     *
     * @param int $quality JPEG quality (0-100).
     *
     * @return void
     */
    public function output(int $quality = 90): void
    {
        imagejpeg($this->contents, null, $quality);
    }

    /**
     * @return array<int, int>
     */
    public function getFingerprint(): array
    {
        return $this->fingerprint;
    }
}
