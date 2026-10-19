package com.capeitassist.fixtrackpro

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import org.json.JSONObject
import java.io.BufferedReader
import java.io.InputStreamReader
import java.net.HttpURLConnection
import java.net.URL
import kotlin.concurrent.thread

class MainActivity : AppCompatActivity() {

    private lateinit var raffleTicketNumber: TextView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_raffle)

        // Handle window insets safely
        val rootView = findViewById<android.view.View>(android.R.id.content)
        ViewCompat.setOnApplyWindowInsetsListener(rootView) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        // Initialize UI components matching activity_raffle.xml
        val btnViewPrizes = findViewById<Button>(R.id.btnViewPrizes)
        val btnEnterRaffle = findViewById<Button>(R.id.btnEnterRaffle)

        // Button actions based on assignment wireframes
        btnViewPrizes.setOnClickListener {
            // TODO: Open Prizes List Activity or View
        }

        btnEnterRaffle.setOnClickListener {
            // TODO: Submit or view raffle entry
        }

        fetchRaffleData()
    }

    private fun fetchRaffleData() {
        thread {
            var conn: HttpURLConnection? = null
            try {
                val url = URL("${NetworkConfig.BASE_URL}get_notifications.php")
                conn = url.openConnection() as HttpURLConnection
                conn.requestMethod = "GET"
                conn.connectTimeout = 7000
                conn.readTimeout = 7000

                if (conn.responseCode == HttpURLConnection.HTTP_OK) {
                    val reader = BufferedReader(InputStreamReader(conn.inputStream))
                    val response = reader.use { it.readText() }.trim()

                    if (response.isNotEmpty()) {
                        val jsonResponse = JSONObject(response)
                        val ticket = jsonResponse.optString("raffle_ticket", "")

                        if (ticket.isNotEmpty()) {
                            runOnUiThread {
                                // Update ticket view if available
                            }
                        }
                    }
                }
            } catch (e: Exception) {
                e.printStackTrace()
            } finally {
                conn?.disconnect()
            }
        }
    }
}