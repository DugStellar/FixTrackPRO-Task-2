package com.capeitassist.fixtrackpro

import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.Request
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.google.android.material.appbar.MaterialToolbar

class ReportActivity : AppCompatActivity() {

    private lateinit var etTitle: EditText
    private lateinit var etDescription: EditText
    private lateinit var btnSubmitReport: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_report)

        val toolbar = findViewById<MaterialToolbar>(R.id.toolbar)
        setSupportActionBar(toolbar)
        supportActionBar?.setDisplayHomeAsUpEnabled(true)

        // Handle Back Arrow Button click
        toolbar.setNavigationOnClickListener {
            finish()
        }

        etTitle = findViewById(R.id.etTitle)
        etDescription = findViewById(R.id.etDescription)
        btnSubmitReport = findViewById(R.id.btnSubmitReport)

        btnSubmitReport.setOnClickListener {
            submitReport()
        }
    }

    private fun submitReport() {
        val title = etTitle.text.toString().trim()
        val description = etDescription.text.toString().trim()

        if (title.isEmpty() || description.isEmpty()) {
            Toast.makeText(this, "Please fill in all fields", Toast.LENGTH_SHORT).show()
            return
        }

        val url = "${NetworkConfig.BASE_URL}submit_report.php"
        val queue = Volley.newRequestQueue(this)

        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            { response ->
                Toast.makeText(this, "Report Submitted Successfully!", Toast.LENGTH_SHORT).show()
                finish() // Return to Home Feed
            },
            { error ->
                Toast.makeText(this, "Failed to submit report. Try again.", Toast.LENGTH_SHORT).show()
            }) {
            override fun getParams(): Map<String, String> {
                val params = HashMap<String, String>()
                params["title"] = title
                params["category"] = "General" // Default category since it's not in the form
                params["description"] = description
                params["user_id"] = "1" // Default user ID
                return params
            }
        }

        queue.add(stringRequest)
    }
}