package com.capeitassist.fixtrackpro

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.Request
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import org.json.JSONArray
import org.json.JSONObject

class AdminPanelActivity : AppCompatActivity() {

    private lateinit var tvTotalReports: TextView
    private lateinit var tvInProgress: TextView
    private lateinit var tvResolved: TextView
    private lateinit var tvPending: TextView
    private lateinit var btnViewAllReports: Button
    private lateinit var btnGenerateWinner: Button
    private lateinit var btnManagePrizes: Button
    private lateinit var btnManageUsers: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_admin_panel)

        tvTotalReports = findViewById(R.id.tvTotalReports)
        tvInProgress = findViewById(R.id.tvInProgress)
        tvResolved = findViewById(R.id.tvResolved)
        tvPending = findViewById(R.id.tvPending)

        btnViewAllReports = findViewById(R.id.btnViewAllReports)
        btnGenerateWinner = findViewById(R.id.btnGenerateWinner)
        btnManagePrizes = findViewById(R.id.btnManagePrizes)
        btnManageUsers = findViewById(R.id.btnManageUsers)

        fetchReportStats()

        btnViewAllReports.setOnClickListener {
            startActivity(Intent(this, AllReportsActivity::class.java))
        }

        btnGenerateWinner.setOnClickListener {
            startActivity(Intent(this, WinnerGeneratorActivity::class.java))
        }

        btnManagePrizes.setOnClickListener {
            startActivity(Intent(this, ManagePrizesActivity::class.java))
        }

        btnManageUsers.setOnClickListener {
            startActivity(Intent(this, ManageUsersActivity::class.java))
        }
    }

    private fun fetchReportStats() {
        val url = "${NetworkConfig.BASE_URL}get_reports.php"
        val queue = Volley.newRequestQueue(this)

        val jsonObjectRequest = JsonObjectRequest(
            Request.Method.GET, url, null,
            { response ->
                try {
                    val status = response.optString("status")
                    if (status == "success") {
                        val reportsArray = response.getJSONArray("reports")
                        processStats(reportsArray)
                    }
                } catch (e: Exception) {
                    Toast.makeText(this, "Parsing error: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                Toast.makeText(this, "Stat Update Failed: ${error.message}", Toast.LENGTH_SHORT).show()
            }
        )
        queue.add(jsonObjectRequest)
    }

    private fun processStats(reports: JSONArray) {
        val total = reports.length()
        var inProgress = 0
        var resolved = 0
        var pending = 0

        for (i in 0 until total) {
            val report = reports.getJSONObject(i)
            val status = report.optString("status").lowercase()
            when (status) {
                "in progress" -> inProgress++
                "resolved" -> resolved++
                else -> pending++
            }
        }

        tvTotalReports.text = total.toString()
        tvInProgress.text = inProgress.toString()
        tvResolved.text = resolved.toString()
        tvPending.text = pending.toString()
    }
}