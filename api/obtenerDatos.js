import mysql from 'mysql2/promise';

// Vercel inyectará la variable de entorno 'DATABASE_URL'
// El "Service URI" que te da Aiven para MySQL ya incluye los parámetros SSL necesarios
const pool = mysql.createPool(process.env.DATABASE_URL);

export default async function handler(req, res) {
    try {
        // Ejecutamos la consulta SQL
        // En mysql2, desestructuramos el resultado para obtener el array de "filas"
        const [filas] = await pool.query('SELECT * FROM usuarios LIMIT 10;');
    
        // Devolvemos los datos a tu HTML en formato JSON
        res.status(200).json(filas);
    } catch (error) {
        console.error("Error en base de datos:", error);
        res.status(500).json({ error: 'Error conectando a MySQL' });
    }
}