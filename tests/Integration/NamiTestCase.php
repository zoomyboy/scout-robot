<?php

namespace Tests\Integration;

use Tests\IntegrationTestCase;

abstract class NamiTestCase extends IntegrationTestCase {
    public function localNamiMember($overrides = []) {
        return $this->createNamiMember(array_merge([
            'landId' => 1054,
            'staatsangehoerigkeitId' => 584
        ], $overrides));
    }
}
