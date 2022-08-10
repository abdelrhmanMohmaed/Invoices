<?php

namespace App\Observers;

use App\Models\Section;

class SectionObserver
{
    /**
     * Handle the Section "created" event.
     *
     * @param  \App\Models\Section  $section
     * @return void
     */
    public function created(Section $section)
    {
        //
    }

    /**
     * Handle the Section "updated" event.
     *
     * @param  \App\Models\Section  $section
     * @return void
     */
    public function updated(Section $section)
    {
        $section->products()->update(['active' => $section->active]);
    }

    /**
     * Handle the Section "deleted" event.
     *
     * @param  \App\Models\Section  $section
     * @return void
     */
    public function deleted(Section $section)
    {
        //
    }

    /**
     * Handle the Section "restored" event.
     *
     * @param  \App\Models\Section  $section
     * @return void
     */
    public function restored(Section $section)
    {
        //
    }

    /**
     * Handle the Section "force deleted" event.
     *
     * @param  \App\Models\Section  $section
     * @return void
     */
    public function forceDeleted(Section $section)
    {
        //
    }
}
