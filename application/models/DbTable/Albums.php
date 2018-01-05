<?php

class Application_Model_DbTable_Albums extends Zend_Db_Table_Abstract
{

    protected $_name = 'albums';

    public function getAlbum($id)
	{

	$id = (int)$id;
	$row = $this->fetchRow('id = ' . $id);
	if (!$row) {
		throw new Exception("Could not find row $id");
	}
	return $row->toArray();
	}

	public function addAlbum($artist, $title,$status)
	{
		$data = array(
			'artist' => $artist,
			'title' => $title,
			'status'=>$status
		);
		$this->insert($data);
	}

	public function updateAlbum($request)
	{
		$albums  = [
			'artist'=>$request['artist'], 
			'title'=>$request['title'],
		];
		$id = $request['id'];
		// 		var_dump($request);
		// var_dump($user);die;
		$this->update($albums, 'id = '.(int)$id);
		return $this->getAlbum($id);
	}

	public function deleteAlbum($id)
	{
		$this->delete('id =' . (int)$id);
	}


	public function addStatus($status,$id)
	{
		$albums  = ['status'=>$status];
		$where = 'id ='.$id;
		$this->update($albums, $where);
	}
}

