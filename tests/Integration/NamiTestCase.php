<?php

namespace Tests\Integration;

use Tests\IntegrationTestCase;
use Tests\Traits\SetsUpNamiDatabaseModels;

abstract class NamiTestCase extends IntegrationTestCase {
    use SetsUpNamiDatabaseModels;

    public function localNamiMember($overrides = []) {
        return $this->createNamiMember(array_merge([
            'landId' => 1054,
            'staatsangehoerigkeitId' => 584
        ], $overrides));
    }
}
