<?php

namespace App\Exports;

use App\Models\miscellanea;
use Maatwebsite\Excel\Concerns\FromCollection;

class miscellaneousErrorsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return miscellanea::all();
    }
}
