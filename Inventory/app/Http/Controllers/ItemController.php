<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    public function store(Request $request)
    {
        $item = Item::create($request->all());
        return response()->json($item);
    }

    public function update(Request $request, Item $item)
    {
        $item->update($request->all());
        return response()->json($item);
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json(['success' => true]);
    }

    public function exportCsv()
    {
        $items = Item::all();
        
        $filename = 'stationary_supplies_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Stock No.', 'Description', 'Unit', 'Quantity']);
            
            // Add data rows
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->stock_no,
                    $item->description,
                    $item->unit,
                    $item->quantity
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Alternative method for more complex formatting
    public function exportCsvFormatted()
    {
        $items = Item::all();
        
        $filename = 'stationary_supplies_formatted_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            
            // Add title row
            fputcsv($file, ['STATIONARY SUPPLIES INVENTORY']);
            fputcsv($file, ['Generated on: ' . date('Y-m-d H:i:s')]);
            fputcsv($file, []); // Empty row
            
            // Add CSV headers
            fputcsv($file, ['Stock No.', 'Description', 'Unit', 'Quantity', 'Status']);
            
            // Add data rows with additional formatting
            foreach ($items as $item) {
                $status = $item->quantity > 0 ? 'In Stock' : 'Out of Stock';
                fputcsv($file, [
                    $item->stock_no,
                    $item->description,
                    $item->unit,
                    $item->quantity,
                    $status
                ]);
            }
            
            // Add summary row
            fputcsv($file, []); // Empty row
            fputcsv($file, ['Total Items:', $items->count()]);
            fputcsv($file, ['Items in Stock:', $items->where('quantity', '>', 0)->count()]);
            fputcsv($file, ['Items Out of Stock:', $items->where('quantity', '<=', 0)->count()]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Export in Requisition and Issue Slip format
    public function exportRequisitionSlip()
    {
        $items = Item::all();
        
        $filename = 'requisition_and_issue_slip_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            
            // Title
            fputcsv($file, ['REQUISITION AND ISSUE SLIP']);
            fputcsv($file, []); // Empty row
            
            // Header information rows
            fputcsv($file, ['Entity Name:', '', '', '', '', '', 'Fund Cluster:']);
            fputcsv($file, []); // Empty row
            fputcsv($file, ['Division:', '', '', 'Responsibility Center Code:']);
            fputcsv($file, ['Office:', '', '', 'RIS No.:']);
            fputcsv($file, []); // Empty row
            
            // Main table headers
            fputcsv($file, [
                '', 'Requisition', '', '', 'Stock Available?', '', 'Issue', ''
            ]);
            fputcsv($file, [
                'Stock No.', 'Unit', 'Description', 'Quantity', 'Yes', 'No', 'Quantity', 'Remarks'
            ]);
            
            // Data rows - populate only the requisition columns, leave others blank
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->stock_no,    // Stock No.
                    $item->unit,        // Unit
                    $item->description, // Description
                    $item->quantity,    // Quantity (Requisition)
                    '',                 // Yes (Stock Available) - blank
                    '',                 // No (Stock Available) - blank
                    '',                 // Quantity (Issue) - blank
                    ''                  // Remarks - blank
                ]);
            }
            
            // Add some empty rows for manual entry
            for ($i = 0; $i < 10; $i++) {
                fputcsv($file, ['', '', '', '', '', '', '', '']);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}