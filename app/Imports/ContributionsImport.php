<?php
namespace App\Imports;

use App\Models\Contribution;
use App\Models\User; // Make sure to import the User model if needed
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContributionsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = User::where('name', $row['user_name'])->first();

        return new Contribution([
            'date' => $row['date'],
            'user_id' => $user->id, // Assuming you found the user based on their name
            'description' => $row['description'],
            'amount' => $row['amount'],
        ]);
    }

}
