<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Proposal;
use App\Models\ProposalFile;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProposalFile>
 */
class ProposalFileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ProposalFileID' => (string) Str::uuid(),
            'UploadedBy' => User::inRandomOrder()->first()->UserID,
            'ProposalID' => Proposal::inRandomOrder()->first()->ProposalID,
            'Filename' => $this->faker->word . '.pdf',
            'Filepath' => $this->faker->filePath(),
            'CreatedAt' => $this->faker->date,
        ];
    }
}
