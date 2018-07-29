<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use Tests\Traits\SetsUpNamiDatabaseModels;

abstract class NamiTestCase extends FeatureTestCase {
    use SetsUpNamiDatabaseModels;

    public function localNamiMember($overrides = []) {
        return $this->createNamiMember(array_merge([
            'landId' => 1054,
            'staatsangehoerigkeitId' => 584
        ], $overrides));
    }
}
