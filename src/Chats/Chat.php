<?php

declare(strict_types=1);

namespace HelpScout\Api\Chats;

use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Support\HasCustomer;
use HelpScout\Api\Support\HydratesData;
use HelpScout\Api\Tags\Tag;

class Chat implements Hydratable
{
    use HydratesData;
    use HasCustomer;

    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $beaconId;

    /**
     * @var int|null
     */
    private $mailboxId;

    /**
     * @var \DateTime|null
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $endedAt;

    /**
     * @var string|null
     */
    private $preview;

    /**
     * @var string[]|Collection
     */
    private $tags;

    /**
     * @var TimelineEvent[]|Collection
     */
    private $timeline;

    /**
     * @var Event[]|Collection
     */
    private $events;

    public function __construct()
    {
        $this->tags = new Collection();
        $this->timeline = new Collection();
        $this->events = new Collection();
    }

    public function hydrate(array $data, array $embedded = []): void
    {
        $this->id = $data['id'] ?? null;
        $this->beaconId = $data['beaconId'] ?? null;
        $this->mailboxId = $data['mailboxId'] ?? null;
        $this->createdAt = $this->transformDateTime($data['createdAt'] ?? null);
        $this->endedAt = $this->transformDateTime($data['endedAt'] ?? null);
        $this->preview = $data['preview'] ?? null;

        if (isset($data['customer'])) {
            $this->hydrateCustomer($data['customer']);
        }

        if (isset($data['tags'])) {
            $this->tags = $this->hydrateMany(Tag::class, $data['tags']);
        }

        if (isset($data['timeline'])) {
            $this->timeline = $this->hydrateMany(TimelineEvent::class, $data['timeline']);
        }

        if (isset($embedded['events'])) {
            $this->events = $this->hydrateMany(Event::class, $embedded['events']);
        }
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getBeaconId(): ?string
    {
        return $this->beaconId;
    }

    public function getMailboxId(): ?int
    {
        return $this->mailboxId;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getEndedAt(): ?\DateTime
    {
        return $this->endedAt;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @return Tag[]|Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @return TimelineEvent[]|Collection
     */
    public function getTimeline(): Collection
    {
        return $this->timeline;
    }

    /**
     * @return Event[]|Collection
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }
}

// array(10) {
//     ["id"]=>
//     string(36) "88e779b6-9adf-47e6-8e7d-270d92c4051e"
//     ["beaconId"]=>
//     string(36) "595791d1-3639-48fd-8657-981fde617341"
//     ["mailboxId"]=>
//     int(125385)
//     ["createdAt"]=>
//     string(27) "2021-04-12T06:41:07.576875Z"
//     ["customer"]=>
//     array(5) {
//       ["id"]=>
//       int(269568751)
//       ["type"]=>
//       string(8) "customer"
//       ["first"]=>
//       string(5) "Tomas"
//       ["last"]=>
//       string(6) "GeeGee"
//       ["email"]=>
//       string(17) "tom@helpscout.com"
//     }
//     ["preview"]=>
//     string(7) "Testing"
//     ["customFields"]=>
//     array(0) {
//     }
//     ["tags"]=>
//     array(0) {
//     }
//     ["timeline"]=>
//     array(3) {
//       [0]=>
//       array(4) {
//         ["type"]=>
//         string(11) "page-viewed"
//         ["timestamp"]=>
//         string(27) "2021-04-12T06:40:58.823000Z"
//         ["url"]=>
//         string(55) "https://fiddle.jshell.net/_display/?editor_console=true"
//         ["title"]=>
//         string(13) "Untitled Page"
//       }
//       [1]=>
//       array(5) {
//         ["type"]=>
//         string(13) "beacon-opened"
//         ["timestamp"]=>
//         string(27) "2021-04-12T06:40:59.329000Z"
//         ["url"]=>
//         string(55) "https://fiddle.jshell.net/_display/?editor_console=true"
//         ["title"]=>
//         string(13) "Untitled Page"
//         ["name"]=>
//         string(18) "*Elyse Rating Test"
//       }
//       [2]=>
//       array(4) {
//         ["type"]=>
//         string(12) "chat-started"
//         ["timestamp"]=>
//         string(27) "2021-04-12T06:41:07.390000Z"
//         ["url"]=>
//         string(55) "https://fiddle.jshell.net/_display/?editor_console=true"
//         ["title"]=>
//         string(13) "Untitled Page"
//       }
//     }
//     ["endedAt"]=>
//     string(27) "2021-04-12T06:47:02.365217Z"
//   }
