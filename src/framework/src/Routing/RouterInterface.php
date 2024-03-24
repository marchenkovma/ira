<?php

namespace Aruka\Routing;

use Aruka\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request);
}
