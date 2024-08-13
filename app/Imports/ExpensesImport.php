<?php

namespace App\Imports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExpensesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Expense([
            'date' => $row['date'],
            'contribution_id' => $row['contribution_id'],
            'user_id' => $row['user_id'],
            'description' => $row['description'],
            'amount' => $row['amount'],
            'code' => $row['code'],
            'creator_name' => $row['creator_name'],
        ]);
    }
}
