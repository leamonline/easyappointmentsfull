<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../application/helpers/validation_helper.php';

/**
 * Tests for parse_pet_age_months() helper function.
 */
class VaccinationGateHelperTest extends TestCase
{
    public function test_months_integer(): void
    {
        $this->assertSame(4.0, parse_pet_age_months('4 months'));
    }

    public function test_months_abbreviation_mo(): void
    {
        $this->assertSame(18.0, parse_pet_age_months('18mo'));
    }

    public function test_months_abbreviation_m(): void
    {
        $this->assertSame(6.0, parse_pet_age_months('6m'));
    }

    public function test_years_integer(): void
    {
        $this->assertSame(24.0, parse_pet_age_months('2 years'));
    }

    public function test_years_decimal(): void
    {
        $this->assertSame(18.0, parse_pet_age_months('1.5 years'));
    }

    public function test_years_abbreviation_yr(): void
    {
        $this->assertSame(12.0, parse_pet_age_months('1yr'));
    }

    public function test_years_abbreviation_y(): void
    {
        $this->assertSame(24.0, parse_pet_age_months('2y'));
    }

    public function test_weeks(): void
    {
        $result = parse_pet_age_months('8 weeks');
        $this->assertNotNull($result);
        $this->assertEqualsWithDelta(1.85, $result, 0.1);
    }

    public function test_weeks_abbreviation_wks(): void
    {
        $result = parse_pet_age_months('8 wks');
        $this->assertNotNull($result);
        $this->assertEqualsWithDelta(1.85, $result, 0.1);
    }

    public function test_weeks_abbreviation_w(): void
    {
        $result = parse_pet_age_months('12w');
        $this->assertNotNull($result);
        $this->assertEqualsWithDelta(2.77, $result, 0.1);
    }

    public function test_puppy_keyword(): void
    {
        $this->assertSame(0.0, parse_pet_age_months('puppy'));
    }

    public function test_puppy_keyword_mixed_case(): void
    {
        $this->assertSame(0.0, parse_pet_age_months('Young Puppy'));
    }

    public function test_null_returns_null(): void
    {
        $this->assertNull(parse_pet_age_months(null));
    }

    public function test_empty_string_returns_null(): void
    {
        $this->assertNull(parse_pet_age_months(''));
    }

    public function test_whitespace_only_returns_null(): void
    {
        $this->assertNull(parse_pet_age_months('   '));
    }

    public function test_unparseable_string_returns_null(): void
    {
        $this->assertNull(parse_pet_age_months('old boy'));
    }

    public function test_six_months_exactly(): void
    {
        $this->assertSame(6.0, parse_pet_age_months('6 months'));
    }

    public function test_singular_month(): void
    {
        $this->assertSame(1.0, parse_pet_age_months('1 month'));
    }

    public function test_singular_year(): void
    {
        $this->assertSame(12.0, parse_pet_age_months('1 year'));
    }

    public function test_singular_week(): void
    {
        $result = parse_pet_age_months('1 week');
        $this->assertNotNull($result);
        $this->assertEqualsWithDelta(0.23, $result, 0.05);
    }
}
