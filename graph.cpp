#include <iostream>
#include <string>
using namespace std;

// Constants
const int NUM_CITIES = 4;
const string CITIES[NUM_CITIES] = {"Chicago", "Boston", "Washington", "Denver"};

// Function to get the index of a city
int getCityIndex(const string& city) {
    for (int i = 0; i < NUM_CITIES; i++) {
        if (CITIES[i] == city) {
            return i;
        }
    }
    return -1; // City not found
}

// Function to find a flight
void findFlight(string flightNumbers[NUM_CITIES][NUM_CITIES], 
                double flightCosts[NUM_CITIES][NUM_CITIES], 
                const string& cityA, 
                const string& cityB) {
    int indexA = getCityIndex(cityA);
    int indexB = getCityIndex(cityB);

    if (indexA == -1 || indexB == -1) {
        cout << "Invalid city.\n";
        return;
    }

    if (!flightNumbers[indexA][indexB].empty()) {
        cout << "Flight Found:\n";
        cout << "Flight Number: " << flightNumbers[indexA][indexB] << "\n";
        cout << "Cost: $" << flightCosts[indexA][indexB] << "\n";
    } else {
        cout << "No flight found from " << cityA << " to " << cityB << ".\n";
    }
}

int main() {
    // Adjacency matrix for flight numbers
    string flightNumbers[NUM_CITIES][NUM_CITIES] = {};

    // Adjacency matrix for flight costs
    double flightCosts[NUM_CITIES][NUM_CITIES] = {};

    // Adding flights
    flightNumbers[getCityIndex("Chicago")][getCityIndex("Boston")] = "AA101";
    flightCosts[getCityIndex("Chicago")][getCityIndex("Boston")] = 150.50;

    flightNumbers[getCityIndex("Washington")][getCityIndex("Denver")] = "UA202";
    flightCosts[getCityIndex("Washington")][getCityIndex("Denver")] = 220.75;

    flightNumbers[getCityIndex("New York")][getCityIndex("Philadelphia")] = "DL303";
    flightCosts[getCityIndex("New York")][getCityIndex("Philadelphia")] = 120.30;

    // Test cases
    cout << "Test Case 1: CITYA = Chicago, CITYB = Boston\n";
    findFlight(flightNumbers, flightCosts, "Chicago", "Boston");
    cout << "\n";

    cout << "Test Case 2: CITYA = Washington, CITYB = Denver\n";
    findFlight(flightNumbers, flightCosts, "Washington", "Denver");
    cout << "\n";

    cout << "Test Case 3: CITYA = New York, CITYB = Philadelphia\n";
    findFlight(flightNumbers, flightCosts, "New York", "Philadelphia");
    cout << "\n";

    cout << "Test Case 4: CITYA = Chicago, CITYB = Denver\n";
    findFlight(flightNumbers, flightCosts, "Chicago", "Denver");
    cout << "\n";

    return 0;
}