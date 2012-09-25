<?php

interface IFileSystemOperation
{
	/**
	 * Удаление элемента файловой системы
	 */
	public function delete();
	
	/**
	 * Переименование элемента файловой системы
	 * @param string $newname - новое имя элемента
	 */	
	public function rename($newname);
	
	/**
	 * Перемещение элемента файловой системы
	 * @param string $dest
	 */
	public function moveTo($dest);
	
	/**
	 * Копирование элемента файловой системы
	 * @param string $dest
	 */
	public function copy($dest);
}

?>