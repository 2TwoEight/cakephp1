<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\SleepRecord> $sleepRecords
 * @var int $weekOffset
 * @var string $period
 * @var float $totalSleepCycles
 * @var float $averageSleepCycles
 * @var int $consecutiveDays
 * @var string $totalCyclesIndicator
 * @var string $consecutiveDaysIndicator
 * @var \App\Model\Entity\SleepRecord $lastRecord
 * @var float $lastRecordPercentage
 */

function getMondayAndSunday($date) {
    $monday = date('Y-m-d', strtotime('monday this week', strtotime($date)));
    $sunday = date('Y-m-d', strtotime('sunday this week', strtotime($date)));
    return [$monday, $sunday];
}

function getMonthName($date) {
    return date('F', strtotime($date));
}

$previousMonth = '';
?>
<div class="sleepRecords index content">
    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'Week')"><?= __('Weekly Summary') ?></button>
        <button class="tablinks" onclick="openTab(event, 'Month')"><?= __('Monthly Summary') ?></button>
    </div>

    <div id="Week" class="tabcontent" style="display: <?= $period === 'week' ? 'block' : 'none' ?>;">
        <div class="navigation-buttons">
            <?= $this->Html->link(__('Previous Week'), ['action' => 'weeklySummary', $weekOffset - 1, 'week'], ['class' => 'button week-button']) ?>
            <?= $this->Html->link(__('Next Week'), ['action' => 'weeklySummary', $weekOffset + 1, 'week'], ['class' => 'button week-button']) ?>
        </div>
        <h3><?= __('Sleep Records - Weekly Summary') ?></h3>
        <?php if (!empty($sleepRecords)): ?>
            <?php list($monday, $sunday) = getMondayAndSunday($sleepRecords[0]->date); ?>
            <h4><?= __('Week from ') . $monday . __(' to ') . $sunday ?></h4>
        <?php else: ?>
            <h4><?= __('No sleep records available for this week.') ?></h4>
        <?php endif; ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><?= __('Date') ?></th>
                        <th><?= __('Sleep Cycles') ?></th>
                        <th><?= __('Mood') ?></th>
                        <th><?= __('Sport') ?></th>
                        <th><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sleepRecords as $sleepRecord): ?>
                        <?php
                        $currentMonth = getMonthName($sleepRecord->date);
                        if ($currentMonth !== $previousMonth) {
                            echo '<tr><td colspan="5"><strong>' . $currentMonth . '</strong></td></tr>';
                            $previousMonth = $currentMonth;
                        }
                        ?>
                        <tr>
                            <td><?= h($sleepRecord->date) ?></td>
                            <td><?= $this->Number->format($sleepRecord->sleep_cycles) ?></td>
                            <td><?= h($sleepRecord->mood) ?></td>
                            <td><?= $sleepRecord->sport ? __('Yes') : __('No') ?></td>
                            <td><?= $this->Html->link(__('View'), ['action' => 'view', $sleepRecord->id]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="summary-details">
            <h4><?= __('Total Sleep Cycles: ') ?><span class="stat-value"><?= $totalSleepCycles ?></span></h4>
            <p class="stat-description"><?= __('Total number of sleep cycles recorded this week.') ?></p>

            <h4><?= __('Average Sleep Cycles: ') ?><span class="stat-value"><?= number_format($averageSleepCycles, 2) ?></span></h4>
            <p class="stat-description"><?= __('Average number of sleep cycles per day.') ?></p>

            <h4><?= __('Consecutive Days with >= 5 Cycles: ') ?><span class="stat-value"><?= $consecutiveDays ?></span> <span style="color: <?= $consecutiveDaysIndicator ?>;">●</span></h4>
            <p class="stat-description"><?= __('Number of consecutive days with at least 5 sleep cycles.') ?></p>

            <h4><?= __('Last Record Percentage: ') ?><span class="stat-value"><?= $averageSleepCycles != 0 ? number_format(($lastRecord->sleep_cycles / $averageSleepCycles) * 100, 2) : 'N/A' ?>%</span></h4>
            <p class="stat-description"><?= __('Percentage of sleep cycles from the last recorded day compared to the average sleep cycles per day.') ?></p>

            <h4><?= __('Total Cycles Indicator: ') ?><span style="color: <?= $totalCyclesIndicator ?>;">●</span></h4>
            <p class="stat-description"><?= __('Indicator showing if the total sleep cycles are within a healthy range.') ?></p>
        </div>
        <div class="charts-container">
            <canvas id="sleepTrackingChartWeek" width="400" height="200"></canvas>
        </div>
    </div>

    <div id="Month" class="tabcontent" style="display: <?= $period === 'month' ? 'block' : 'none' ?>;">
        <div class="navigation-buttons">
            <?= $this->Html->link(__('Previous Month'), ['action' => 'weeklySummary', $weekOffset - 1, 'month'], ['class' => 'button month-button']) ?>
            <?= $this->Html->link(__('Next Month'), ['action' => 'weeklySummary', $weekOffset + 1, 'month'], ['class' => 'button month-button']) ?>
        </div>
        <h3><?= __('Sleep Records - Monthly Summary') ?></h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><?= __('Date') ?></th>
                        <th><?= __('Sleep Cycles') ?></th>
                        <th><?= __('Mood') ?></th>
                        <th><?= __('Sport') ?></th>
                        <th><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sleepRecords as $sleepRecord): ?>
                        <?php
                        $currentMonth = getMonthName($sleepRecord->date);
                        if ($currentMonth !== $previousMonth) {
                            echo '<tr><td colspan="5"><strong>' . $currentMonth . '</strong></td></tr>';
                            $previousMonth = $currentMonth;
                        }
                        ?>
                        <tr>
                            <td><?= h($sleepRecord->date) ?></td>
                            <td><?= $this->Number->format($sleepRecord->sleep_cycles) ?></td>
                            <td><?= h($sleepRecord->mood) ?></td>
                            <td><?= $sleepRecord->sport ? __('Yes') : __('No') ?></td>
                            <td><?= $this->Html->link(__('View'), ['action' => 'view', $sleepRecord->id]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="summary-details">
            <h4><?= __('Total Sleep Cycles: ') ?><span class="stat-value"><?= $totalSleepCycles ?></span></h4>
            <p class="stat-description"><?= __('Total number of sleep cycles recorded this month.') ?></p>

            <h4><?= __('Average Sleep Cycles: ') ?><span class="stat-value"><?= number_format($averageSleepCycles, 2) ?></span></h4>
            <p class="stat-description"><?= __('Average number of sleep cycles per day.') ?></p>

            <h4><?= __('Consecutive Days with >= 5 Cycles: ') ?><span class="stat-value"><?= $consecutiveDays ?></span> <span style="color: <?= $consecutiveDaysIndicator ?>;">●</span></h4>
            <p class="stat-description"><?= __('Number of consecutive days with at least 5 sleep cycles.') ?></p>

            <h4><?= __('Last Record Percentage: ') ?><span class="stat-value"><?= $averageSleepCycles != 0 ? number_format(($lastRecord->sleep_cycles / $averageSleepCycles) * 100, 2) : 'N/A' ?>%</span></h4>
            <p class="stat-description"><?= __('Percentage of sleep cycles from the last recorded day compared to the average sleep cycles per day.') ?></p>

            <h4><?= __('Total Cycles Indicator: ') ?><span style="color: <?= $totalCyclesIndicator ?>;">●</span></h4>
            <p class="stat-description"><?= __('Indicator showing if the total sleep cycles are within a healthy range.') ?></p>
        </div>
        <div class="charts-container">
            <canvas id="sleepTrackingChartMonth" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<style>
    .charts-container {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .charts-container canvas {
        max-width: 100%;
    }
    .navigation-buttons {
        margin-bottom: 10px;
    }
    .week-button {
        display: none;
    }
    .month-button {
        display: none;
    }
    .summary-details h4 {
        display: inline-block;
        margin-right: 20px;
    }
    .stat-value {
        font-weight: bold;
    }
    .stat-description {
        margin-bottom: 10px;
        font-size: 0.9em;
        color: #555;
    }
</style>

<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";

        // Show or hide navigation buttons
        var weekButtons = document.getElementsByClassName("week-button");
        var monthButtons = document.getElementsByClassName("month-button");
        if (tabName === 'Week') {
            for (i = 0; i < weekButtons.length; i++) {
                weekButtons[i].style.display = "inline-block";
            }
            for (i = 0; i < monthButtons.length; i++) {
                monthButtons[i].style.display = "none";
            }
        } else {
            for (i = 0; i < weekButtons.length; i++) {
                weekButtons[i].style.display = "none";
            }
            for (i = 0; i < monthButtons.length; i++) {
                monthButtons[i].style.display = "inline-block";
            }
        }

        // Save the active tab to localStorage
        localStorage.setItem('activeTab', tabName);

        // Initialize charts for the selected tab
        initializeCharts(tabName);
    }

    function initializeCharts(tabName) {
        var ctx;
        if (tabName === 'Week') {
            ctx = document.getElementById('sleepTrackingChartWeek').getContext('2d');
        } else {
            ctx = document.getElementById('sleepTrackingChartMonth').getContext('2d');
        }

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_map(function($record) { return $record->date; }, $sleepRecords)) ?>,
                datasets: [{
                    type: 'bar',
                    label: 'Sleep Cycles',
                    data: <?= json_encode(array_map(function($record) { return $record->sleep_cycles; }, $sleepRecords)) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    type: 'line',
                    label: 'Mood',
                    data: <?= json_encode(array_map(function($record) { return $record->mood; }, $sleepRecords)) ?>,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 2,
                    fill: false,
                    yAxisID: 'y-axis-mood'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Sleep Cycles'
                        }
                    },
                    'y-axis-mood': {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Mood'
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw;
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // Open the default tab or the saved tab
    window.onload = function() {
        var activeTab = localStorage.getItem('activeTab') || 'Week';
        document.getElementById(activeTab).style.display = "block";
        var tablinks = document.getElementsByClassName("tablinks");
        for (var i = 0; i < tablinks.length; i++) {
            if (tablinks[i].textContent === activeTab + ' Summary') {
                tablinks[i].className += " active";
            }
        }

        // Show or hide navigation buttons based on the active tab
        var weekButtons = document.getElementsByClassName("week-button");
        var monthButtons = document.getElementsByClassName("month-button");
        if (activeTab === 'Week') {
            for (i = 0; i < weekButtons.length; i++) {
                weekButtons[i].style.display = "inline-block";
            }
            for (i = 0; i < monthButtons.length; i++) {
                monthButtons[i].style.display = "none";
            }
        } else {
            for (i = 0; i < weekButtons.length; i++) {
                weekButtons[i].style.display = "none";
            }
            for (i = 0; i < monthButtons.length; i++) {
                monthButtons[i].style.display = "inline-block";
            }
        }

        // Initialize charts for the active tab
        initializeCharts(activeTab);
    };
</script>
