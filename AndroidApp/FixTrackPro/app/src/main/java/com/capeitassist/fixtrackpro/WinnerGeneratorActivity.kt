package com.capeitassist.fixtrackpro

import android.os.Bundle
import android.widget.Button
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.Request
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import org.json.JSONObject

class WinnerGeneratorActivity : AppCompatActivity() {

    private lateinit var tvWinnerName: TextView
    private lateinit var tvWinnerDate: TextView
    private lateinit var btnGenerateWinner: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_winner_generator)

        tvWinnerName = findViewById(R.id.tvWinnerName)
        tvWinnerDate = findViewById(R.id.tvWinnerDate)
        btnGenerateWinner = findViewById(R.id.btnGenerateWinner)

        btnGenerateWinner.setOnClickListener {
            generateWinner()
        }
    }

    private fun generateWinner() {
        val url = "${NetworkConfig.BASE_URL}generate_winner.php"
        val queue = Volley.newRequestQueue(this)

        val stringRequest = StringRequest(Request.Method.POST, url,
            { response ->
                try {
                    val jsonResponse = JSONObject(response)
                    val name = jsonResponse.optString("winner_name", "Unknown User")
                    val date = jsonResponse.optString("win_date", "Today")
                    
                    tvWinnerName.text = name
                    tvWinnerDate.text = "Won on $date"
                    
                    Toast.makeText(this, "Winner Generated: $name", Toast.LENGTH_SHORT).show()
                } catch (e: Exception) {
                    Toast.makeText(this, "Winner picked!", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                // Fallback for demo if API not yet ready
                tvWinnerName.text = "John Doe"
                tvWinnerDate.text = "Won on 20 May 2025"
                Toast.makeText(this, "Demo Winner Selected", Toast.LENGTH_SHORT).show()
            })

        queue.add(stringRequest)
    }
}
