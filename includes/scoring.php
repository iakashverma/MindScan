<?php
declare(strict_types=1);

function compute_scores(array $answers): array
{
    $weights = [1.2, 1.1, 1.0, 1.2, 1.1, 1.0, 1.1, 1.2, 0.7, 1.0];

    $values = [
        $answers[1] ?? 1,
        $answers[2] ?? 1,
        $answers[3] ?? 1,
        $answers[4] ?? 1,
        $answers[5] ?? 1,
        $answers[6] ?? 1,
        $answers[7] ?? 1,
        $answers[8] ?? 1,
        $answers[9] ?? 1,
        6 - ($answers[10] ?? 1),
    ];

    $raw = 0.0;
    $max = 0.0;
    foreach ($values as $index => $value) {
        $raw += $value * $weights[$index];
        $max += 5 * $weights[$index];
    }

    $risk_score = (int)round(($raw / $max) * 100);

    $stress_score = (int)round((($values[3] + $values[7] + $values[1] + $values[5]) / 20) * 100);
    $sleep_score = (int)round((($values[4] + $values[0]) / 10) * 100);
    $productivity_score = (int)round((($values[2] + $values[6] + $values[5]) / 15) * 100);

    $mental_health_score = (int)round(($risk_score + $stress_score + $sleep_score + $productivity_score) / 4);
    $emotional_score = (int)round(100 - (($values[7] + $values[1]) / 10) * 100);

    $risk_level = 'Low Risk';
    if ($risk_score >= 70) {
        $risk_level = 'High Risk';
    } elseif ($risk_score >= 35) {
        $risk_level = 'Moderate Risk';
    }

    $sentiment_index = ($values[1] + $values[3] + $values[7]) / 3;
    $sentiment_label = 'Balanced';
    if ($sentiment_index >= 4.0) {
        $sentiment_label = 'Overwhelmed';
    } elseif ($sentiment_index >= 2.5) {
        $sentiment_label = 'Uneasy';
    }

    $ai_summary = 'Your responses indicate a stable relationship with social media.';
    if ($risk_level === 'High Risk') {
        $ai_summary = 'Your responses show a high mental strain linked to social media use.';
    } elseif ($risk_level === 'Moderate Risk') {
        $ai_summary = 'Your responses show early warning signs of stress and distraction.';
    }

    return [
        'risk_score' => $risk_score,
        'stress_score' => $stress_score,
        'sleep_score' => $sleep_score,
        'productivity_score' => $productivity_score,
        'mental_health_score' => $mental_health_score,
        'emotional_score' => $emotional_score,
        'risk_level' => $risk_level,
        'sentiment_label' => $sentiment_label,
        'ai_summary' => $ai_summary,
    ];
}
