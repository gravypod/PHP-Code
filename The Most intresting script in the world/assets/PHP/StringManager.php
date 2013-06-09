<?php

	class StringManager
	{

		public $lines = array();

		private $width = 0;

		private $height = 0;

		public function StringManager($fontWidth, $fontHeight, $lineSpacing, $quote)
		{

			$tempString = "";
			$stringLength = strlen($quote);
			$hadOpenQuote = false;
			for ($i = 0; $i < $stringLength; $i++) {

				$tempString .= $quote[$i];

				$lastCharacter = $tempString[strlen($tempString) - 1]; // find the last char of the temp str

				/*if ($lastCharacter === ",") { // Check for places to break
					$this->addNextLine($fontWidth, $fontHeight, $lineSpacing, $tempString);
				} else */if (($lastCharacter === "," or $lastCharacter === "." or $lastCharacter === "!" or $lastCharacter === "?") and !(isset($quote[$i + 1]) and ($quote[$i + 1] === "\"" or $quote[$i + 1] === "."))) { // Keep quotes intact

					if ($lastCharacter == "\"" and $hadOpenQuote) {
						$hadOpenQuote = true;
					} else if ($lastCharacter == "\"" and !$hadOpenQuote) {
						$hadOpenQuote = false;
					} else {
						$this->addNextLine($fontWidth, $fontHeight, $lineSpacing, $tempString);
					}

				}


			}

			if ($tempString == ".") { // account for any last/un-pushed strings
				$this->lines[count($this->lines)] = trim($tempString);
			} else {
				$this->addNextLine($fontWidth, $fontHeight, $lineSpacing, $tempString);
			}


		}

		private function addNextLine(&$fontWidth, &$fontHeight, $lineSpacing, &$line)
		{
			$trimmedLine = trim($line);

			$this->lines[count($this->lines) + 1] = $trimmedLine;

			$lastWidth = strlen($trimmedLine) * $fontWidth;

			$this->updateMeasurements($fontHeight, $lineSpacing, $lastWidth);

			$line = "";

		}

		private function updateMeasurements(&$fontHeight, $lineSpacing, $lastWidth)
		{
			if ($lastWidth > $this->width) {
				$this->width = $lastWidth;
			}
			$this->height += $fontHeight + $lineSpacing;
		}

		public function getLines()
		{
			return $this->lines;
		}

		public function  getHeight()
		{

			return $this->height;
		}

		public function  getWidth()
		{

			if (Utils::getDefaultWidth() > $this->width) {
				return Utils::getDefaultWidth();
			}

			return $this->width;
		}

	}