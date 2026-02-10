<?php

namespace App\Enums;

enum CommentCategory: string
{
    case POSITIVE = 'positive';
    case NEGATIVE = 'negative';
    case SUPPORTIVE = 'supportive';
    case CRITICISM = 'criticism';
    case SUGGESTION = 'suggestion';
    case QUESTION = 'question';
    case TOXIC = 'toxic';
    case NEUTRAL = 'neutral';

    public function getLabel(): string
    {
        return match ($this) {
            self::POSITIVE => __('comments.category.positive'),
            self::NEGATIVE => __('comments.category.negative'),
            self::SUPPORTIVE => __('comments.category.supportive'),
            self::CRITICISM => __('comments.category.criticism'),
            self::SUGGESTION => __('comments.category.suggestion'),
            self::QUESTION => __('comments.category.question'),
            self::TOXIC => __('comments.category.toxic'),
            self::NEUTRAL => __('comments.category.neutral'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::POSITIVE => 'green',
            self::NEGATIVE => 'red',
            self::SUPPORTIVE => 'blue',
            self::CRITICISM => 'orange',
            self::SUGGESTION => 'purple',
            self::QUESTION => 'yellow',
            self::TOXIC => 'red',
            self::NEUTRAL => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::POSITIVE => 'heroicon-o-face-smile',
            self::NEGATIVE => 'heroicon-o-face-frown',
            self::SUPPORTIVE => 'heroicon-o-heart',
            self::CRITICISM => 'heroicon-o-exclamation-triangle',
            self::SUGGESTION => 'heroicon-o-light-bulb',
            self::QUESTION => 'heroicon-o-question-mark-circle',
            self::TOXIC => 'heroicon-o-fire',
            self::NEUTRAL => 'heroicon-o-minus-circle',
        };
    }
}
