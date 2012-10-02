<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EntityFxCaptcha
 *
 * @author EntityFX
 */
class EntityFxCaptchaAction extends CCaptchaAction {

    const NOISE_DOTS_DEFAULT = 50;

    protected function renderImage($code) {
        $image = $this->createImage();

        $foreColor = imagecolorallocate($image, (int) ($this->foreColor % 0x1000000 / 0x10000), (int) ($this->foreColor % 0x10000 / 0x100), $this->foreColor % 0x100);

        if ($this->fontFile === null)
            $this->fontFile = dirname(__FILE__) . '/consola.ttf';

        $length = strlen($code);
        $box = imagettfbbox(30, 0, $this->fontFile, $code);
        $w = $box[4] - $box[0] + $this->offset * ($length - 1);
        $h = $box[1] - $box[5];
        $scale = min(($this->width - $this->padding * 2) / $w, ($this->height - $this->padding * 2) / $h);
        $x = 10;
        $y = round($this->height * 27 / 40);

        $this->dotNoizeRender($image);

        for ($i = 0; $i < $length; ++$i) {
            $fontSize = (int) (mt_rand(26, 38) * $scale * 0.8);
            $angle = mt_rand(-10, 10);
            $letter = $code[$i];
            $foreColor = mt_rand(0, 0x7FFFFF);
            $box = imagettftext($image, $fontSize, $angle, $x, $y, $foreColor, $this->fontFile, $letter);
            $x = $box[2] + $this->offset;
        }

        $this->dotNoizeRender($image);

        imagecolordeallocate($image, $foreColor);

        $image = $this->addWave($image);

        $this->dotNoizeRender($image);

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header("Content-type: image/png");
        imagepng($image);
        imagedestroy($image);
    }

    private function createImage() {
        $outputImage = imagecreatetruecolor($this->width, $this->height);

        $backColor = imagecolorallocate($outputImage, (int) ($this->backColor % 0x1000000 / 0x10000), (int) ($this->backColor % 0x10000 / 0x100), $this->backColor % 0x100);
        imagefilledrectangle($outputImage, 0, 0, $this->width, $this->height, $backColor);
        imagecolordeallocate($outputImage, $backColor);

        if ($this->transparent)
            imagecolortransparent($outputImage, $backColor);

        return $outputImage;
    }

    protected function dotNoizeRender($image) {
        for ($dotNoize = 0; $dotNoize < self::NOISE_DOTS_DEFAULT; $dotNoize++) {
            imagesetpixel($image, rand(0, $this->width), rand(0, $this->height), rand(0, 0xDFFFFF));
        }
    }

    protected function addWave($image) {
        $outputImage = $this->createImage();

        // случайные параметры (можно поэкспериментировать с коэффициентами):
        // частоты
        $freq1 = mt_rand(700000, 1000000) / 15000000;
        $freq2 = mt_rand(700000, 1000000) / 15000000;
        $freq3 = mt_rand(700000, 1000000) / 15000000;
        $freq4 = mt_rand(700000, 1000000) / 15000000;

        // фазы
        $phase1 = mt_rand(0, 3141592) / 1000000;
        $phase2 = mt_rand(0, 3141592) / 1000000;
        $phase3 = mt_rand(0, 3141592) / 1000000;
        $phase4 = mt_rand(0, 3141592) / 1000000;

        // амплитуды
        $ampl1 = mt_rand(200, 400) / 100;
        $ampl2 = mt_rand(200, 400) / 100;

        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {
                // координаты пикселя-первообраза.
                $sx = $x + ( sin($x * $freq1 + $phase1) + sin($y * $freq3 + $phase2) ) * $ampl1;
                $sy = $y + ( sin($x * $freq2 + $phase3) + sin($y * $freq4 + $phase4) ) * $ampl2;

                // первообраз за пределами изображения
                if ($sx < 0 || $sy < 0 || $sx >= $this->width - 1 || $sy >= $this->height - 1) {
                    $color = 255;
                    $color_x = 255;
                    $color_y = 255;
                    $color_xy = 255;
                } else { // цвета основного пикселя и его 3-х соседей для лучшего антиалиасинга
                    $color = (imagecolorat($image, $sx, $sy) >> 16) & 0xFF;
                    $color_x = (imagecolorat($image, $sx + 1, $sy) >> 16) & 0xFF;
                    $color_y = (imagecolorat($image, $sx, $sy + 1) >> 16) & 0xFF;
                    $color_xy = (imagecolorat($image, $sx + 1, $sy + 1) >> 16) & 0xFF;
                }

                // сглаживаем только точки, цвета соседей которых отличается
                if ($color == $color_x && $color == $color_y && $color == $color_xy) {
                    $newcolor = $color;
                } else {
                    $frsx = $sx - floor($sx); //отклонение координат первообраза от целого
                    $frsy = $sy - floor($sy);
                    $frsx1 = 1 - $frsx;
                    $frsy1 = 1 - $frsy;

                    // вычисление цвета нового пикселя как пропорции от цвета основного пикселя и его соседей
                    $newcolor = floor($color * $frsx1 * $frsy1 +
                            $color_x * $frsx * $frsy1 +
                            $color_y * $frsx1 * $frsy +
                            $color_xy * $frsx * $frsy);
                }
                imagesetpixel($outputImage, $x, $y, imagecolorallocate($outputImage, $newcolor, $newcolor, $newcolor));
            }
        }
        return $outputImage;
    }

}

?>
