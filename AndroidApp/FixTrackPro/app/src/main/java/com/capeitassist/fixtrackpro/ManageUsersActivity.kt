package com.capeitassist.fixtrackpro

import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.Request
import com.android.volley.toolbox.JsonArrayRequest
import com.android.volley.toolbox.Volley

class ManageUsersActivity : AppCompatActivity() {

    private lateinit var rvUsers: RecyclerView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_manage_users)

        rvUsers = findViewById(R.id.rvUsers)
        rvUsers.layoutManager = LinearLayoutManager(this)

        fetchUsers()
    }

    private fun fetchUsers() {
        val url = "${NetworkConfig.BASE_URL}get_users.php"
        val queue = Volley.newRequestQueue(this)

        val jsonArrayRequest = JsonArrayRequest(Request.Method.GET, url, null,
            { response ->
                // Typically attach to an adapter here
                Toast.makeText(this, "${response.length()} users loaded", Toast.LENGTH_SHORT).show()
            },
            { error ->
                Toast.makeText(this, "Failed to load users", Toast.LENGTH_SHORT).show()
            })

        queue.add(jsonArrayRequest)
    }
}
