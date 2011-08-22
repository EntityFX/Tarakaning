<?php

interface IFileSystemOperation
{
	/**
	 * �������� �������� �������� �������
	 */
	public function delete();
	
	/**
	 * �������������� �������� �������� �������
	 * @param string $newname - ����� ��� ��������
	 */	
	public function rename($newname);
	
	/**
	 * ����������� �������� �������� �������
	 * @param string $dest
	 */
	public function moveTo($dest);
	
	/**
	 * ����������� �������� �������� �������
	 * @param string $dest
	 */
	public function copy($dest);
}

?>