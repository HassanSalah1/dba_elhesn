<?php
namespace App\Repositories\Dashboard\Order\Setting;

use App\Models\Shipment;
use App\Repositories\General\UtilsRepository;
use Yajra\DataTables\Facades\DataTables;

class ShipmentRepository
{

    // get Shipments and create datatable data.
    public static function getShipmentsData(array $data)
    {
        $shipments = Shipment::withTrashed()
            ->orderBy('id', 'DESC');
        return DataTables::of($shipments)
            ->editColumn('image', function ($shipment) {
                if ($shipment->image !== null && file_exists($shipment->image)) {
                    return '<a href="' . url($shipment->image) . '" data-popup="lightbox">
                                <img src="' . url($shipment->image) . '" class="img-rounded img-preview"
                                style="max-height:50px;max-width:50px;"></a>';
                }
            })
            ->addColumn('actions', function ($shipment) {
                $ul = '';
                if ($shipment->deleted_at === null) {
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.edit') . '" id="' . $shipment->id . '" onclick="editShipment(this);return false;" href="#" class="on-default edit-row btn btn-info"><i data-feather="edit"></i></a>
                   ';
//                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.delete_action') . '" id="' . $shipment->id . '" onclick="deleteShipment(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                } else {
//                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.restore_action') . '" id="' . $shipment->id . '" onclick="restoreShipment(this);return false;" href="#" class="on-default remove-row btn btn-success"><i data-feather="refresh-cw"></i></a>';
                }
                return $ul;
            })->make(true);
    }

    public static function addShipment(array $data)
    {
        $shipmentData = [
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'],
            'price' => $data['price'],
        ];
        $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
        $image_name = 'image';
        $image_path = 'uploads/shipments/';
        $image = UtilsRepository::createImage($data['request'], $image_name, $image_path, $file_id);
        if ($image !== false) {
            $shipmentData['image'] = $image;
            $created = Shipment::create($shipmentData);
            if ($created) {
                return true;
            }
        }
        return false;
    }

    public static function deleteShipment(array $data)
    {
        $shipment = Shipment::where(['id' => $data['id']])->first();
        if ($shipment) {
            $shipment->delete();
            return true;
        }
        return false;
    }

    public static function restoreShipment(array $data)
    {
        $shipment = Shipment::withTrashed()->where(['id' => $data['id']])->first();
        if ($shipment) {
            $shipment->restore();
            return true;
        }
        return false;
    }

    public static function getShipmentData(array $data)
    {
        $shipment = Shipment::where(['id' => $data['id']])->first();
        if ($shipment) {
            $shipment->image = $shipment->image ? url($shipment->image) : null;
            return $shipment;
        }
        return false;
    }

    public static function editShipment(array $data)
    {
        $shipment = Shipment::where(['id' => $data['id']])->first();
        if ($shipment) {
            $shipmentData = [
                'name_ar' => $data['name_ar'],
                'name_en' => $data['name_en'],
                'price' => $data['price'],
            ];
            $file_id = 'IMG_' . mt_rand(00000, 99999) . (time() + mt_rand(00000, 99999));
            $image_name = 'image';
            $image_path = 'uploads/shipments/';
            $image = UtilsRepository::createImage($data['request'], $image_name, $image_path, $file_id);
            if ($image !== false) {
                $shipmentData['image'] = $image;
                if ($shipment->image && file_exists($shipment->image)) {
                    unlink($shipment->image);
                }
            }
            $updated = $shipment->update($shipmentData);
            if ($updated) {
                return true;
            }
        }
        return false;
    }

}

?>
