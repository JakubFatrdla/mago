<?php

/**
 * @return array{pool: string, process-manager: 'dynamic'|'ondemand'|'static', start-time: int<0, max>, start-since: int<0, max>, accepted-conn: int<0, max>, listen-queue: int<0, max>, max-listen-queue: int<0, max>, listen-queue-len: int<0, max>, idle-processes: int<0, max>, active-processes: int<1, max>, total-processes: int<1, max>, max-active-processes: int<1, max>, max-children-reached: 0|1, slow-requests: int<0, max>, procs: array<int, array{pid: int<2, max>, state: 'Idle'|'Running', start-time: int<0, max>, start-since: int<0, max>, requests: int<0, max>, request-duration: int<0, max>, request-method: string, request-uri: string, query-string: string, request-length: int<0, max>, user: string, script: string, last-request-cpu: float, last-request-memory: int<0, max>}>}|false
 */
function fpm_get_status(): array|false {}

/**
 * @return bool
 */
function fastcgi_finish_request() {}
