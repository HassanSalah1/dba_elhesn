<?php
namespace App\Repositories\Dashboard\Order;

use App\Entities\OrderStatus;
use App\Entities\OrderType;
use App\Entities\OrderUserType;
use App\Entities\UserRoles;
use App\Models\Order;
use Yajra\DataTables\Facades\DataTables;

class OrderRepository
{

    // get Orders and create datatable data.
    public static function getOrdersData(array $data)
    {
        $statuses = [];
        if ($data['status'] === 'new') {
            $statuses = [
                OrderStatus::WAIT,
                OrderStatus::ACCEPTED,
                OrderStatus::REFUSED,
                OrderStatus::EDITED,
                OrderStatus::PAYMENT_APPROVED,
                OrderStatus::PROGRESS
            ];
        } else if ($data['status'] === 'progress') {
            $statuses = [
                OrderStatus::SHIPPED
            ];
        } else if ($data['status'] === 'completed') {
            $statuses = [
                OrderStatus::COMPLETED,
            ];
        } else if ($data['status'] === 'canceled') {
            $statuses = [
                OrderStatus::CANCELLED,
                OrderStatus::RECEIVE_REFUSED_APPROVED
            ];
        } else if ($data['status'] === 'refused') {
            $statuses = [
                OrderStatus::RECEIVE_REFUSED,
            ];
        }

        $orders = Order::whereIn('type', [
            OrderType::DAMAIN, OrderType::DIRECT,
            OrderType::BID, OrderType::NEGOTIATION
        ])
            ->whereIn('status', $statuses)
            ->orderBy('id', 'DESC');

        return DataTables::of($orders)
            ->addColumn('buyer', function ($order) {
                $html = '<ul>';
                if ($order->user_type === OrderUserType::BUYER ) {
                    if ($order->user){
                        $html .= '<li>' . $order->user->name . '</li>';
                        $html .= '<li>' . $order->user->full_phone . '</li>';
                        if ($order->user->role === UserRoles::REGISTER) {
                            $html .= '<li>' . trans('admin.invitation_sent') . '</li>';
                        }
                    }
                } else if ($order->other_user) {
                    $html .= '<li>' . $order->other_user->name . '</li>';
                    $html .= '<li>' . $order->other_user->full_phone . '</li>';
                    if ($order->other_user->role === UserRoles::REGISTER) {
                        $html .= '<li>' . trans('admin.invitation_sent') . '</li>';
                    }
                }
                $html .= '</ul>';
                return $html;
            })
            ->addColumn('seller', function ($order) {
                $html = '<ul>';
                if ($order->user_type === OrderUserType::SELLER) {
                    $html .= '<li>' . $order->user->name . '</li>';
                    $html .= '<li>' . $order->user->full_phone . '</li>';
                    if ($order->user->role === UserRoles::REGISTER) {
                        $html .= '<li>' . trans('admin.invitation_sent') . '</li>';
                    }
                } else if ($order->other_user) {
                    $html .= '<li>' . $order->other_user->name . '</li>';
                    $html .= '<li>' . $order->other_user->full_phone . '</li>';
                    if ($order->other_user->role === UserRoles::REGISTER) {
                        $html .= '<li>' . trans('admin.invitation_sent') . '</li>';
                    }
                }
                $html .= '</ul>';
                return $html;
            })
            ->editColumn('status', function ($order) {
                return trans('admin.' . $order->status . '_status');
            })
            ->editColumn('type', function ($order) {
                return trans('admin.' . $order->type . '_type');
            })
            ->addColumn('refuse_reason', function ($order) {
                if ($order->status == OrderStatus::RECEIVE_REFUSED) {
                    return @$order->refuse_reason->reason;
                }
            })
            ->addColumn('actions', function ($order) {
                $ul = '';
                $ul .= '<a data-toggle="tooltip" title="' . trans('admin.details_action') . '" id="' . $order->id . '" href="' . url('/admin/order/details/' . $order->id) . '" class="on-default edit-row btn btn-info"><i data-feather="eye"></i></a>
                   ';
                if ($order->status == OrderStatus::RECEIVE_REFUSED) {
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.approve_action') . '" id="' . $order->id . '" onclick="approveRequest(this);return false;" href="#" class="on-default remove-row btn btn-success"><i data-feather="check"></i></a> ';
                    $ul .= '<a data-toggle="tooltip" title="' . trans('admin.refuse_action') . '" id="' . $order->id . '" onclick="refuseRequest(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                }
                return $ul;
            })->make(true);
    }

    public static function approveOrderRefuseRequest(array $data)
    {
        $order = Order::where([
            'id' => $data['id'],
            'status' => OrderStatus::RECEIVE_REFUSED
        ])->first();
        if ($order) {
            $order->update([
                'status' => OrderStatus::RECEIVE_REFUSED_APPROVED
            ]);
            // TODO: send notification
            return true;
        } else {
            return false;
        }
    }

    public static function refuseOrderRefuseRequest(array $data)
    {
        $order = Order::where([
            'id' => $data['id'],
            'status' => OrderStatus::RECEIVE_REFUSED
        ])->first();
        if ($order) {
            $order->update([
                'status' => OrderStatus::COMPLETED
            ]);
            // TODO: send notification
            return true;
        } else {
            return false;
        }
    }

}

?>
