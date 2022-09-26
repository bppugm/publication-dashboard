<?php

namespace Tests\Feature\Models;

use App\Models\Dashboard;
use App\Models\Data;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created_using_factory()
    {
        $dashboard = Dashboard::factory()->create();

        $this->assertDatabaseHas('dashboards', [
            'id' => $dashboard->id
        ]);
    }

    /** @test */
    public function it_belongs_to_many_data()
    {
        $dashboard = Dashboard::factory()->create();
        $data = Data::factory(2)->create();

        $dashboard->data()->attach($data);

        $this->assertCount(2, $dashboard->data);
    }

    /** @test */
    public function it_can_extract_data_ids()
    {
        $scopus = Data::factory()->create(['value' => 1767, 'name' => 'scopus']);
        $sinta1 = Data::factory()->create(['value' => 10, 'name' => 'sinta 1']);
        $sinta2 = Data::factory()->create(['value' => 20, 'name' => 'sinta 2']);
        $sinta3 = Data::factory()->create(['value' => 48, 'name' => 'sinta 3']);
        $citationCurrent = Data::factory()->create(['value' => 16207, 'name' => 'citation current']);
        $citationTotal = Data::factory()->create(['value' => 112779, 'name' => 'citation total']);

        $dashboard = Dashboard::create([
            'name' => 'default dashboard',
            'description' => null,
            'widgets' => [
                [
                    "x" => 0,
                    "y" => 0,
                    "w" => 4,
                    "h" => 5,
                    "i" => "0",
                    "type" => "numeric",
                    "title" => "Publikasi Terindeks di Scopus 2022",
                    "values" => [["text" => $scopus->id, "type" => "data"]],
                    "unit" => "judul",
                    "ribbonText" => "Scopus",
                ],
                [
                    "x" => 0,
                    "y" => 10,
                    "w" => 4,
                    "h" => 5,
                    "i" => "1",
                    "type" => "numeric",
                    "title" => "Sitasi di Scopus 2022 / Akumulasi",
                    "values" => [
                        ["text" => $citationCurrent->id, "type" => "data"],
                        ["text" => "/", "type" => "text"],
                        ["text" => $citationTotal->id, "type" => "data"],
                    ],
                ],
                [
                    "x" => 4,
                    "y" => 0,
                    "w" => 4,
                    "h" => 5,
                    "i" => "2",
                    "type" => "numeric",
                    "title" => "Publikasi Books and Chapters",
                    "values" => 155,
                    "unit" => "judul",
                ],
                [
                    "x" => 4,
                    "y" => 5,
                    "w" => 4,
                    "h" => 10,
                    "i" => "3",
                    "type" => "chart",
                    "title" => "Perjanjian Kerja - IKU",
                    "chartOptions" => [
                        "data" => [
                            "labels" => ["Target", "Capaian"],
                            "datasets" => [
                                [
                                    "label" => "",
                                    "data" => [1, 0.39],
                                    "backgroundColor" => [
                                        "rgba(255, 99, 132, 1)",
                                        "rgba(255, 159, 64, 1)",
                                    ],
                                    "borderColor" => [
                                        "rgb(255, 99, 132)",
                                        "rgb(255, 159, 64)",
                                    ],
                                    "borderWidth" => 1,
                                ],
                            ],
                        ],
                        "options" => ["scales" => ["y" => ["beginAtZero" => true]]],
                    ],
                ],
                [
                    "x" => 8,
                    "y" => 0,
                    "w" => 4,
                    "h" => 5,
                    "i" => "4",
                    "type" => "numeric",
                    "title" => "Jurnal terakreditasi SINTA",
                    "values" => [
                        [
                            "text" => "s1 + s2 + s3",
                            "type" => "expression",
                            "variables" => [
                                ["text" => "s1", "data_id" => $sinta1->id],
                                ["text" => "s2", "data_id" => $sinta2->id],
                                ["text" => "s3", "data_id" => $sinta3->id],
                            ],
                        ],
                    ],
                    "unit" => "Jurnal",
                ],
                [
                    "x" => 8,
                    "y" => 5,
                    "w" => 4,
                    "h" => 10,
                    "i" => "5",
                    "type" => "chart",
                    "title" => "Mandat capaian kinerja",
                    "chartOptions" => [
                        "data" => [
                            "labels" => [
                                "IKK 1",
                                "IKK 2",
                                "IKK 3",
                                "IKK 4",
                                "IKK 5",
                                "IKK 6",
                            ],
                            "datasets" => [
                                [
                                    "label" => "",
                                    "data" => [64.75, 70.45, 101.84, 108.33, 212.5, 159.43],
                                    "backgroundColor" => ["rgba(255, 99, 132, 1)"],
                                ],
                            ],
                        ],
                        "options" => ["scales" => ["y" => ["beginAtZero" => true]]],
                    ],
                ],
                [
                    "x" => 0,
                    "y" => 5,
                    "w" => 4,
                    "h" => 5,
                    "i" => "6",
                    "type" => "numeric",
                    "title" => "Jurnal terindeks Scopus",
                    "values" => 10,
                    "unit" => "jurnal",
                ],
            ]
        ]);

        $extractedIds = $dashboard->extractedDataIds();
        $dataIds = [$scopus->id, $sinta1->id, $sinta2->id, $sinta3->id, $citationTotal->id, $citationCurrent->id];
        $this->assertEmpty(
            collect($extractedIds)->diff($dataIds)
        );
    }
}
