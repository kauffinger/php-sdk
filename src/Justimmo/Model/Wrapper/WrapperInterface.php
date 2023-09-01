<?php

namespace Justimmo\Model\Wrapper;

interface WrapperInterface
{
    /**
     * transforms the return of a list call into a ListPager
     *
     *
     * @return mixed
     */
    public function transformList($data);

    /**
     * transforms the return value of a detail call into a model object
     *
     *
     * @return mixed
     */
    public function transformSingle($data);
}
