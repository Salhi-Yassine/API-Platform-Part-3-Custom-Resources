<?php
namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;

// I do want a get item operation... but if anybody goes to it, 
// I want to execute the NotFoundAction which will
// rudely return a 404 response.
/**
* @ApiResource(
*   itemOperations={
*       "get"={
*           "method"="GET",
*           "controller"=NotFoundAction::class,
*           "read"=false,
*           "output"=false,
*       },
*   },
* )*/
class DailyStats
{
    
    public $date;
    public $totalVisitors;
    public $mostPopularListings;

    /**
    * @ApiProperty(identifier=true)
    */
    public function getDateString(): string
    {
        return $this->date->format('Y-m-d');
    }
}
