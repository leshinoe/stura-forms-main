<?php

namespace Tests\Feature\Casts;

use App\Casts\Iban;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IbanCastTest extends TestCase
{
    #[Test]
    public function it_formats_iban_correctly_with_no_spaces()
    {
        $model = new IbanTestModel([
            'iban' => 'DE89370400440532013000',
        ]);

        $this->assertEquals('DE89 3704 0044 0532 0130 00', $model->iban);
    }

    #[Test]
    public function it_formats_iban_correctly_with_spaces()
    {
        $model = new IbanTestModel([
            'iban' => 'DE8 93704004 405320130 00',
        ]);

        $this->assertEquals('DE89 3704 0044 0532 0130 00', $model->iban);
    }

    #[Test]
    public function it_trims_iban_correctly()
    {
        $model = new IbanTestModel([
            'iban' => 'DE89370400440532013000 ',
        ]);

        $this->assertEquals('DE89 3704 0044 0532 0130 00', $model->iban);
    }
}

class IbanTestModel extends Model
{
    protected $casts = [
        'iban' => Iban::class,
    ];
}
