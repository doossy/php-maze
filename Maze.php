<?php
class Maze{
    // Maze Create
    private $_w;
    private $_h;
    private $_grids;
    private $_walkHistory;
    private $_walkHistory2;
    private $_targetSteps;

    // Construct
    public function Maze() {
        $this->_w = 6;
        $this->_h = 6;
        $this->_grids = array();
    }

    // 设置迷宫大小
    public function set($width = 6, $height = 6) {
        if ( $width > 0 ) $this->_w = $width;
        if ( $height > 0 ) $this->_h = $height;
        return $this;
    }

    // 取到迷宫
    public function get() {
        return $this->_grids;
    }

    // 生成迷宫
    public function create() {
        $this->_init();
        return $this->_walk(rand(0, count($this->_grids) -1 ));
    }

    // 获取死胡同点
    public function block($n = 0, $rand = false) {
        $l = count($this->_grids);
        for( $i = 1; $i < $l; $i++ ) {
            $v = $this->_grids[$i];
            if ( $v == 1 || $v == 2 || $v == 4 || $v == 8 ) {
                $return[] = $i;
            }
        }

        // 随机取点
        if ( $rand ) shuffle($return);

        if ( $n == 0 ) return $return;

        if ( $n == 1 ) {
            return array_pop($return);
        } else {
            return array_slice($return, 0, $n);
        }
    }

    /**
    |---------------------------------------------------------------
    | 生成迷宫的系列函数
    |---------------------------------------------------------------
    */
    // 从开始遍历
    private function _walk($startPos) {
        $this->_walkHistory = array();
        $this->_walkHistory2 = array();
        $curPos = $startPos;
        while ($this->_getNext0() != -1) {
            $curPos = $this->_step($curPos);
            if ( $curPos === false ) break;
        }
        return $this;
    }

    // 生成指定点的四个方向
    private function _getTargetSteps($curPos) {
        $p = 0;
        $a = array();

        // 上
        $p = $curPos - $this->_w;

        if ($p > 0 && $this->_grids[$p] === 0 && ! $this->_isRepeating($p)) {
            array_push($a, $p);
        } else {
            array_push($a, -1);
        }

        // 右
        $p = $curPos + 1;
        if ($p % $this->_w != 0 && $this->_grids[$p] === 0 && ! $this->_isRepeating($p)) {
            array_push($a, $p);
        } else {
            array_push($a, -1);
        }

        // 下
        $p = $curPos + $this->_w;
        if ($p < count($this->_grids) && $this->_grids[$p] === 0 && ! $this->_isRepeating($p)) {
            array_push($a, $p);
        } else {
            array_push($a, -1);
        }

        // 左
        $p = $curPos - 1;
        if (($curPos % $this->_w) != 0 && $this->_grids[$p] === 0 && ! $this->_isRepeating($p)) {
            array_push($a, $p);
        } else {
            array_push($a, -1);
        }

        return $a;
    }

    // 四个方向都遍历过
    private function _noStep() {
        $l = count($this->_targetSteps);
        for ($i = 0; $i < $l; $i ++) {
            if ($this->_targetSteps[$i] != -1) return false;
        }
        return true;
    }

    // 生成格子
    private function _step($curPos) {
        // 取当前格子的四个方向
		$this->_targetSteps = $this->_getTargetSteps($curPos);
		if ( $this->_noStep() ) {
            // 回退
            if ( count($this->_walkHistory) > 0 ) {
                $tmp = array_pop($this->_walkHistory);
            } else {
			    return false;
            }
			array_push($this->_walkHistory2, $tmp);
			return $this->_step($tmp);
		}

        // 随机一个方向走
        do{
            $r = rand(0, 3);
        }while($this->_targetSteps[$r] == -1 );

		$nextPos = $this->_targetSteps[$r];

		$isCross = false;
		if ( $this->_grids[$nextPos] != 0)
			$isCross = true;

        // 开放路线
		if ($r == 0) {
			$this->_grids[$curPos] ^= 1;
			$this->_grids[$nextPos] ^= 4;
		} elseif ($r == 1) {
			$this->_grids[$curPos] ^= 2;
			$this->_grids[$nextPos] ^= 8;
		} elseif ($r == 2) {
			$this->_grids[$curPos] ^= 4;
			$this->_grids[$nextPos] ^= 1;
		} elseif ($r == 3) {
			$this->_grids[$curPos] ^= 8;
			$this->_grids[$nextPos] ^= 2;
		}
		array_push($this->_walkHistory, $curPos);

		return $isCross ? false : $nextPos;
    }

    // 是否已经访问过
    private function _isRepeating($p) {
        $l = count($this->_walkHistory);
		for ($i = 0; $i < $l; $i ++) {
			if ($this->_walkHistory[$i] == $p) return true;
		}
        $l = count($this->_walkHistory2);
		for ($i = 0; $i < $l; $i ++) {
			if ($this->_walkHistory2[$i] == $p) return true;
		}
		return false;
    }

    // 取一个没有访问的点
    private function _getNext0() {
        $l = count($this->_grids);

        for ($i = 0; $i <= $l; $i++ ) {
			if ( $this->_grids[$i] == 0) return $i;
		}
		return -1;
    }

    // 初始化，所有的点都设置成0
    private function _init() {
		$this->_grids = array();
		for ($y = 0; $y < $this->_h; $y ++) {
			for ($x = 0; $x < $this->_w; $x ++) {
				array_push($this->_grids, 0);
			}
        }
		return $this;
    }
}
