<?php
namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;

// I do want a get item operation... but if anybody goes to it, 
// I want to execute the NotFoundAction which will
// rudely return a 404 response.
/**
* @ApiResource(
*   normalizationContext={"groups"={"daily-stats:read"}},
*   itemOperations={
*       "get"={
*           "method"="GET",
*           "controller"=NotFoundAction::class,
*           "read"=false,
*           "output"=false,
*       }
*   },
*  collectionOperations={"get"}
* )*/
class DailyStats
{
    
    public $date;
     /**
     * @Groups({"daily-stats:read", "daily-stats:write"})
     */
    public $totalVisitors;

    /**
     * The 5 most popular cheese listings from this date!
     *
     * @var array<CheeseListing>|CheeseListing[]
     * @Groups({"daily-stats:read"})
     */
    public $mostPopularListings;

    public function __construct(\DateTimeInterface $date, int $totalVisitors, array $mostPopularListings)
    {
        $this->date = $date;
        $this->totalVisitors = $totalVisitors;
        $this->mostPopularListings = $mostPopularListings;
    }

    /**
    * @ApiProperty(identifier=true)
    */
    public function getDateString(): string
    {
        return $this->date->format('Y-m-d');
    }
}
