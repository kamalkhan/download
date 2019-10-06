<?php

namespace Bhittani\Download;

abstract class Download implements Contract, CallbackContract
{
    use AcceptsCallback;
}
