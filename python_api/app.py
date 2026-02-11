from flask import Flask, request, jsonify

app = Flask(__name__)


# Route de test
@app.route('/health', methods=['GET'])
def health():
    return jsonify({
        "status": "ok",
        "message": "API Flask fonctionne"
    })


# Route analyse
@app.route('/analyse', methods=['POST'])
def analyse():

    try:
        # Lire JSON
        data = request.get_json()

        if not data:
            return jsonify({
                "success": False,
                "message": "Aucune donnée reçue"
            }), 400

        projet_id = data.get('projet_id')
        text = data.get('text')

        # Vérification
        if not projet_id and not text:
            return jsonify({
                "success": False,
                "message": "Aucun texte ou ID de projet reçu pour l'analyse"
            }), 400

        print("Projet ID :", projet_id)
        print("Text :", text)

        # =========================
        # Simulation analyse
        # =========================

        resultats = {
            "score_qualite": 85,

            "erreurs": [
                {
                    "categorie_id": 1,
                    "fichier": "main.py",
                    "ligne": 12,
                    "description": "Variable non utilisée",
                    "gravite": "moyenne",
                    "suggestion": "Supprimer la variable"
                },
                {
                    "categorie_id": 2,
                    "fichier": "index.html",
                    "ligne": 5,
                    "description": "Balise non fermée",
                    "gravite": "faible",
                    "suggestion": "Fermer la balise"
                }
            ]
        }

        return jsonify({
            "success": True,
            "message": "Analyse terminée",
            "resultats": resultats
        })


    except Exception as e:

        return jsonify({
            "success": False,
            "message": "Erreur serveur : " + str(e)
        }), 500


if __name__ == '__main__':
    app.run(debug=True, port=5000)