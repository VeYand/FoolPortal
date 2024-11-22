import {App} from 'app/App'
import 'app/styles/index.css'
import {createRoot} from 'react-dom/client'
import {Provider} from 'react-redux'
import {BrowserRouter} from 'react-router-dom'
import {store} from 'shared/redux'
import {foolPortalApi} from './shared/redux/api'

foolPortalApi.init()
const root = document.getElementById('root')

if (root) {
	createRoot(root).render(
		<Provider store={store}>
			<BrowserRouter>
				<App/>
			</BrowserRouter>
		</Provider>,
	)
}