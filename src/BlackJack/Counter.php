<?php

namespace App\BlackJack;

class Counter
{
    /**
     * @var array<string, int> Array mapping card values to their counts
     */
    private array $cardStats;
    private int $highCards;
    private int $lowCards;
    private int $maintainCards;


    public function __construct()
    {
        $this->cardStats = [];
        $this->highCards = 120;
        $this->lowCards = 120;
        $this->maintainCards = 72;
    }
    /**
     * @SuppressWarnings(PHPMD)
     */
    public function updateStatistics(string $cardValue): void
    {
        if (isset($this->cardStats[$cardValue])) {
            $this->cardStats[$cardValue]++;
        } else {
            $this->cardStats[$cardValue] = 1;
        }
    }
    /**
     * @return array<string, int> Array mapping card values to their counts
     */
    public function getStatistics(): array
    {

        return $this->cardStats;
    }
    /**
     * @SuppressWarnings(PHPMD)
     */
    public function getRunningCount(): int
    {
        $runningCount = 0;
        $this->highCards = 120;
        $this->lowCards = 120;
        $this->maintainCards = 72;
        foreach ($this->cardStats as $cardValue => $count) {
            if (in_array($cardValue, ['2', '3', '4', '5', '6'])) {
                $runningCount += $count;
                $this->lowCards -= $count;
            } elseif (in_array($cardValue, ['10', 'Knekt', 'Dam', 'Kung', 'Ess'])) {
                $runningCount -= $count;
                $this->highCards -= $count;
            } else {
                $this->maintainCards -= $count;

            }
        }

        return $runningCount;
    }
    /**
     * @SuppressWarnings(PHPMD)
     */
    public function probabilityOfBusting(int $currentHandValue): float
    {
        $this->getRunningCount();
        $remainingCards = $this->highCards + $this->lowCards + $this->maintainCards;
        $bustCount = 0;

        for ($i = 2; $i <= 14; $i++) {
            // Check if drawing this card value would cause a bust
            if ($i + $currentHandValue > 21) {
                // If the card value is 10 or higher (10, J, Q, K, A), adjust bust count based on high cards
                if ($i >= 10) {
                    $bustCount += $this->highCards / 5; // Divide by 5 for each type of high card (10, J, Q, K, A)
                } elseif($i <= 6) {
                    // For card values 2 to 6, adjust bust count based on low cards
                    $bustCount += $this->lowCards / 5; // Divide by 5 for each type of low card
                } else {
                    $bustCount += $this->maintainCards / 3; // Divide by 3 for each type of maintain card
                }
            }
        }
        // Calculate the probability
        if ($remainingCards == 0) {
            $remainingCards = 1;
        }

        $probabilityOfBusting = $bustCount / $remainingCards;

        return round($probabilityOfBusting * 100, 1);
    }
}
