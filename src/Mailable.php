<?php

declare(strict_types=1);

namespace Dmyers\Phoney;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable as BaseMailable;
use Illuminate\Queue\SerializesModels;

class Mailable extends BaseMailable
{
    use Queueable, SerializesModels;
}
