<?php

// FamilyMember model removed. The family module was intentionally removed.
// Kept for reference only — do not use in application code.

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    protected $guarded = [];

    public function __call($method, $args)
    {
        abort(410, 'FamilyMember model removed');
    }
}
