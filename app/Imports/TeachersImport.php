<?php

namespace App\Imports;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Validators\Failure;

class TeachersImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    WithChunkReading,
    SkipsOnFailure
{
    use SkipsErrors, Importable, RegistersEventListeners;

    public function collection(Collection $rows)
    {
        foreach($rows as $row){
            $password = Str::random(7);
            $user = User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => Hash::make($password),
                'gender' => $row['gender']
            ]);

            $user->assignRole('teacher');
            $user->notify(new WelcomeNotification($user->name, $password));
        }
    }

    public function rules(): array
    {
        return [
            '*.email' => ['email', 'unique:users']
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.email.unique' => 'Duplicate email already found',
        ];
    }

    public function chunkSize(): int
    {
        return 10;
    }

    public static function afterImport(AfterImport $event)
    {
        //TODO: Send notification here.
    }

    public function onFailure(Failure ...$failures)
    {
        // TODO: Implement onFailure() method.
        return $failures;

    }
}
