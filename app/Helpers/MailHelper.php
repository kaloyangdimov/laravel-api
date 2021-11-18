<?php

function sendEmail($template, $mailData, $to, $subject) {
    try {
        \Mail::send($template, $mailData, function ($m) use ($to, $subject) {
            $m->from(config('mail.from.address'), config('mail.from.name'));
            $m->to($to);
            $m->subject($subject);
        });
    } catch (\Exception $e) {
        logger()->error('Send email error: '. $e->getMessage());
        return false;
    }

    return empty(\Mail::failures());
}
