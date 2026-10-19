package com.capeitassist.fixtrackpro

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.Request
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.google.android.material.bottomnavigation.BottomNavigationView

class GuestFeedActivity : AppCompatActivity() {

    private lateinit var rvReports: RecyclerView
    private lateinit var tvWelcomeUser: TextView
    private lateinit var tvStatActive: TextView
    private lateinit var bottomNav: BottomNavigationView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_guest_feed)

        tvWelcomeUser = findViewById(R.id.tvWelcomeUser)
        tvStatActive = findViewById(R.id.tvStatActive)
        rvReports = findViewById(R.id.rvReports)
        bottomNav = findViewById(R.id.bottomNavigation)

        // Setup RecyclerView
        rvReports.layoutManager = LinearLayoutManager(this)

        // Load logged in user's name if available
        val sharedPref = getSharedPreferences("FixTrackPrefs", Context.MODE_PRIVATE)
        val userName = sharedPref.getString("user_name", "Guest")
        tvWelcomeUser.text = "Welcome, $userName!"

        // Configure Bottom Navigation
        bottomNav.selectedItemId = R.id.nav_feed
        bottomNav.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_feed -> true
                R.id.nav_report -> {
                    startActivity(Intent(this, ReportActivity::class.java))
                    true
                }
                R.id.nav_raffle -> {
                    // Navigate to Raffle or open Toast if feature in progress
                    Toast.makeText(this, "Raffle Feature Coming Soon!", Toast.LENGTH_SHORT).show()
                    true
                }
                R.id.nav_profile -> {
                    startActivity(Intent(this, SettingsActivity::class.java))
                    true
                }
                else -> false
            }
        }

        // Fetch reports from backend
        fetchReports()
    }

    private fun fetchReports() {
        val url = "${NetworkConfig.BASE_URL}get_reports.php"
        val queue = Volley.newRequestQueue(this)

        val jsonObjectRequest = JsonObjectRequest(
            Request.Method.GET, url, null,
            { response ->
                try {
                    val status = response.getString("status")
                    if (status == "success") {
                        val reportsArray = response.getJSONArray("reports")
                        tvStatActive.text = reportsArray.length().toString()

                        // Parse JSON into list and set to adapter
                        // val adapter = ReportAdapter(reportList)
                        // rvReports.adapter = adapter
                    }
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            },
            { error ->
                Toast.makeText(this, "Failed to load feed", Toast.LENGTH_SHORT).show()
            }
        )

        queue.add(jsonObjectRequest)
    }
}