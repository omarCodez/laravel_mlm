// App/Models/Board.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Board extends Model
{
    protected $fillable = ['name', 'max_slots', 'filled_slots', 'status', 'leader_id'];

    // Relationships
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'board_user', 'board_id', 'user_id');
    }

    // Methods to check if the board is full
    public function isFull()
    {
        return $this->filled_slots >= $this->max_slots;
    }

    public function addUser(User $user)
    {
        if (!$this->isFull()) {
            $this->users()->attach($user);
            $this->increment('filled_slots');
        }

        if ($this->isFull()) {
            $this->status = 'closed';
            $this->save();
        }
    }
}
