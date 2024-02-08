<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    public static array $DOMAINS = ['triage', 'teaching', 'managing', 'leading', 'learning'];

    public static array $REQUIRED_JSON_FIELDS = [
        'domain', 'element', 'generated_variable', 'question'
    ];
    public static array $OTHER_JSON_FIELDS = [
        'advice',
        'dependencies',
        'description',
        'domain_print',
        'element_description',
        'element_print',
        'indicator',
        'indicator_print',
        'phase',
        'phase_description',
        'question_example',
        'variable_suffix',
    ];
    public static array $NON_EMPTY_FIELDS = [
        'domain',
        'description',
        'element',
        'generated_variable',
        'indicator',
        'phase',
        'variable_suffix',
        'phase_description',
        'domain_print',
        'element_print',
        'indicator_print',
    ];

    protected $fillable = [
        //------------------------------------------
        // common fields
        //------------------------------------------
        'domain',
        "domain_print",
        'advice',
        "description",
        'question',
        'question_example',
        "variable_suffix",
        "generated_variable",
        "dependencies",
        "phase",
        "phase_description",


        //------------------------------------------
        // mapped fields
        //------------------------------------------
        "chapter", // aka element
        "category", // aka indicator
        "chapter_print",  // aka element_print
        "category_print", // aka indicator_print
        "chapter_description", //aka element_description

        //------------------------------------------
        // Introduced fields
        //------------------------------------------
        'survey_id',
    ];

    public static function isValidQuestion(array $question): ?string
    {
        foreach (Question::$REQUIRED_JSON_FIELDS as $required) {
            if (!array_key_exists($required, $question)) {
                return 'Question is missing required field: ' . $required;
            }
        }
        $domain = $question['domain'];
        if (!array_key_exists('domain', $question)) {
            return 'Question is missing the required \'domain\' field';
        }
        if (!in_array($domain, Question::$DOMAINS, true)) {
            return 'Question contained invalid domain: ' . $domain;
        }

        if ($question['domain'] != 'triage') {
            // check for all other fields
            foreach (Question::$OTHER_JSON_FIELDS as $required) {
                if (!array_key_exists($required, $question)) {
                    return 'Non-triage question is missing required field: ' . $required;
                }

            }
        }
        // validate all values are strings
        foreach ($question as $key => $value) {
            if (!is_string($value)) {
                return 'Expected string value for: ' . $key . ' received value: ' . $value;
            }
            if (empty($value) && in_array($key, Question::$NON_EMPTY_FIELDS, true)) {
                return 'Field ' . $key . ' must not be empty';
            }
        }
        return null;
    }


    public function __toString()
    {
        return parent::__toString();
    }

}
