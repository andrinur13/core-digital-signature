<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('createParentTree')) {
    function createParentTree($data, $parrent, $id, $module) {
		$str = '';
		if(isset($data[$parrent])){ 
			
			foreach($data[$parrent] as $value){
				$idStr = $id .'.'. $value['UnitId'];
				$child = createParentTree($data, $value['UnitId'], $idStr, $module);
				if( $child ){					
					$str .= '<tr data-tt-id="'. $idStr .'" data-tt-parent-id="'. $id .'">
								<td>'. $value['UnitKode'] .'</td>
								<td>'. $value['UnitName'] .'</td>
								<td>
								<div class="btn-group">
									<button class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
									<div class="dropdown-menu" x-placement="top-start" style="position: absolute; top: -157px; left: 0px; will-change: top, left;">
										<a class="dropdown-item" data-original-title="Edit data '. $value['UnitKode'] .'" data-rel="tooltip" data-placement="bottom" href="'. site_url($module . '/update/' .  $value['UnitId']) .'">Edit</a>
									</div>
							  	</div>
								</td>
							  </tr>'. $child;
				} else {
					$str .= '<tr data-tt-id="'. $idStr .'"  data-tt-parent-id="'. $id .'">
								<td>'. $value['UnitKode'] .'</td>
								<td>'. $value['UnitName'] .'</td>
								<td>
								<div class="btn-group">
									<button class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
									<div class="dropdown-menu" x-placement="top-start" style="position: absolute; top: -157px; left: 0px; will-change: top, left;">
										<a class="dropdown-item" data-original-title="Edit data '. $value['UnitKode'] .'" data-rel="tooltip" data-placement="bottom" href="'. site_url($module . '/update/' .  $value['UnitId']) .'">Edit</a>
										<a class="dropdown-item" data-original-title="Delete data unit'. $value['UnitKode'] .'" data-rel="tooltip" data-placement="bottom" href="'. site_url($module . '/delete/' .  $value['UnitId']) .'">Delete</a>
									</div>
								</div>
								</td>
							  </tr>';
				}
			}
		}
		
		return $str;
    }
}