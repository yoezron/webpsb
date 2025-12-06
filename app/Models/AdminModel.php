<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'admin_panitia';
    protected $primaryKey       = 'id_admin';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'password_hash',
        'nama_lengkap',
        'email',
        'role_panitia',
        'is_active',
        'last_login',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'username'      => 'required|min_length[3]|max_length[50]|is_unique[admin_panitia.username,id_admin,{id_admin}]',
        'password_hash' => 'required',
        'email'         => 'permit_empty|valid_email|is_unique[admin_panitia.email,id_admin,{id_admin}]',
        'role_panitia'  => 'required|in_list[tsanawiyyah,muallimin,superadmin]',
    ];

    protected $validationMessages = [
        'username' => [
            'required'   => 'Username harus diisi',
            'is_unique'  => 'Username sudah digunakan',
        ],
        'email' => [
            'valid_email' => 'Format email tidak valid',
            'is_unique'   => 'Email sudah digunakan',
        ],
        'role_panitia' => [
            'required' => 'Role harus dipilih',
            'in_list'  => 'Role tidak valid',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Hash password before insert/update
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            unset($data['data']['password']);
        }

        return $data;
    }

    /**
     * Verify login credentials
     */
    public function verifyLogin($username, $password)
    {
        $admin = $this->where('username', $username)
            ->where('is_active', true)
            ->first();

        if (!$admin) {
            return false;
        }

        if (password_verify($password, $admin['password_hash'])) {
            // Update last login
            $this->update($admin['id_admin'], ['last_login' => date('Y-m-d H:i:s')]);
            return $admin;
        }

        return false;
    }

    /**
     * Get admin by role
     */
    public function getByRole($role)
    {
        return $this->where('role_panitia', $role)
            ->where('is_active', true)
            ->findAll();
    }

    /**
     * Change password
     */
    public function changePassword($idAdmin, $newPassword)
    {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($idAdmin, ['password_hash' => $passwordHash]);
    }
}
