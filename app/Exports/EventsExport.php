<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;

class EventsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Event::all();
    }

    public function map($event): array
    {
        return [
            $event->id,
            $event->name,
            $event->description,
            $event->event_date,
            $event->location,
            $event->created_at,
            $event->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Event Date',
            'Location',
            'Created At',
            'Updated At',
        ];
    }
}
