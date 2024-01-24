<?php

use Modules\Report\Models\Report;
use Modules\Report\Models\ReportStatus;

return [
    /**
     * Report workflow graph
     */
    'report' => [
        // class of your domain object
        'class'         => Report::class,

        // name of the graph (default is "default")
        'graph'         => 'report',

        // property of your object holding the actual state (default is "state")
        'property_path' => 'status_id',

        // list of all possible states
        'states'        => [
            [
                'name' => ReportStatus::ID_DRAFT,
            ],
            [
                'name' => ReportStatus::ID_SUBMITTED,
            ],
            [
                'name' => ReportStatus::ID_FAILED,
            ],
            [
                'name' => ReportStatus::ID_COMPLETED,
            ],
            [
                'name' => ReportStatus::ID_PAUSED,
            ],
        ],

        // list of all possible transitions
        'transitions'   => [
            ReportStatus::DRAFT     => [
                'from' => [
                    ReportStatus::ID_SUBMITTED,
                    ReportStatus::ID_COMPLETED,
                    ReportStatus::ID_PAUSED,
                ],
                'to'   => ReportStatus::ID_DRAFT,
            ],
            ReportStatus::PAUSED    => [
                'from' => [
                    ReportStatus::ID_DRAFT,
                    ReportStatus::ID_FAILED,
                ],
                'to'   => ReportStatus::ID_PAUSED,
            ],
            ReportStatus::SUBMITTED => [
                'from' => [
                    ReportStatus::ID_DRAFT,
                    ReportStatus::ID_FAILED,
                ],
                'to'   => ReportStatus::ID_SUBMITTED,
            ],
            ReportStatus::FAILED    => [
                'from' => [
                    ReportStatus::ID_DRAFT,
                    ReportStatus::ID_SUBMITTED,
                    ReportStatus::ID_COMPLETED,
                ],
                'to'   => ReportStatus::ID_FAILED,
            ],
            ReportStatus::COMPLETED => [
                'from' => [
                    ReportStatus::ID_SUBMITTED,
                ],
                'to'   => ReportStatus::ID_COMPLETED,
            ],
        ],

        // list of all callbacks
        'callbacks'     => [
            // will be called when testing a transition
            'guard'  => [],

            // will be called before applying a transition
            'before' => [],

            // will be called after applying a transition
            'after'  => [
                'after_submitted' => [
                    // call the callback on a specific transition
                    'on'   => ReportStatus::SUBMITTED,
                    // will call the method of this class
                    'do'   => ['state-machine.report.states.submitted', 'after'],
                    // arguments for the callback
                    'args' => ['object'],
                ],
                'after_completed' => [
                    // call the callback on a specific transition
                    'on'   => ReportStatus::COMPLETED,
                    // will call the method of this class
                    'do'   => ['state-machine.report.states.completed', 'after'],
                    // arguments for the callback
                    'args' => ['object'],
                ],
            ],
        ],
    ],
];
