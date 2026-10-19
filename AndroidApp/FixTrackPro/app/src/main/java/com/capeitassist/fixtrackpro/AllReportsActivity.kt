package com.capeitassist.fixtrackpro

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.android.volley.Request
import com.android.volley.toolbox.JsonArrayRequest
import com.android.volley.toolbox.Volley

class AllReportsActivity : AppCompatActivity() {
    private lateinit var rvAllReports: RecyclerView
    private val reportList = mutableListOf<Report>()
    private lateinit var adapter: ReportAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_guest_feed) // Reusing feed layout
        
        rvAllReports = findViewById(R.id.rvReports)
        rvAllReports.layoutManager = LinearLayoutManager(this)
        adapter = ReportAdapter(reportList)
        rvAllReports.adapter = adapter
        
        fetchAllReports()
    }

    private fun fetchAllReports() {
        val url = "${NetworkConfig.BASE_URL}get_reports.php"
        val queue = Volley.newRequestQueue(this)
        val jsonArrayRequest = JsonArrayRequest(Request.Method.GET, url, null,
            { response ->
                reportList.clear()
                for (i in 0 until response.length()) {
                    val obj = response.getJSONObject(i)
                    reportList.add(Report(
                        obj.optString("title", "No Title"),
                        obj.optString("location", "Unknown"),
                        obj.optString("status", "Pending")
                    ))
                }
                adapter.notifyDataSetChanged()
            },
            { error -> Toast.makeText(this, "Failed to load reports", Toast.LENGTH_SHORT).show() })
        queue.add(jsonArrayRequest)
    }

    data class Report(val title: String, val location: String, val status: String)

    class ReportAdapter(private val reports: List<Report>) : RecyclerView.Adapter<ReportAdapter.ViewHolder>() {
        class ViewHolder(view: View) : RecyclerView.ViewHolder(view) {
            val tvTitle: TextView = view.findViewById(R.id.tvTitle)
            val tvLocation: TextView = view.findViewById(R.id.tvLocation)
            val tvStatus: TextView = view.findViewById(R.id.tvStatus)
        }
        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val view = LayoutInflater.from(parent.context).inflate(R.layout.item_report, parent, false)
            return ViewHolder(view)
        }
        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            val report = reports[position]
            holder.tvTitle.text = report.title
            holder.tvLocation.text = report.location
            holder.tvStatus.text = report.status
        }
        override fun getItemCount() = reports.size
    }
}
