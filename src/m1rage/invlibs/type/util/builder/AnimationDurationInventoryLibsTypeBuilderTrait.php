<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\util\builder;

trait AnimationDurationInventoryLibsTypeBuilderTrait{

	private int $animation_duration = 0;

	public function setAnimationDuration(int $animation_duration) : self{
		$this->animation_duration = $animation_duration;
		return $this;
	}

	protected function getAnimationDuration() : int{
		return $this->animation_duration;
	}
}