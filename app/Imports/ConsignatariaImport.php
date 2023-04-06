<?php

namespace App\Imports;

use App\Models\Consignataria;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ConsignatariaImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue

{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $consignante_id;


    public function __construct($consignante_id)
    {

        $this->consignante_id = $consignante_id;
    }


    public function model(array $row)
    {

        // dd($row);



        $busca = str_replace(['"', '='], '', $row[$this->consignante_id]);
        // dd($busca);

        if (!empty($busca)) {
            $consignataria = Consignataria::where('name', $busca)->exists();
            //dd($consignataria);

            if ($consignataria == false) {


                return new Consignataria([
                    'name' => $busca
                ]);
            }
        }


    }

    public function chunkSize(): int
    {
        return 1000;
        // TODO: Implement chunkSize() method.
    }
}
