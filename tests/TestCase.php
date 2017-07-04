<?php


use Tests\traits\{CreatesApplication, TestsHelper};

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplication, TestsHelper;
}
