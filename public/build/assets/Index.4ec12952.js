import{k as U,m as q,u as I,y as S,s as w,E,t as z,r as o,o as t,c as a,h as k,j as i,e as f,i as _,d as l,F as c,I as P,a as r,z as y,v as M,x as p}from"./app.ca5d1c04.js";const W={class:"space-x-4"},G={class:"overflow-hidden border border-gray-200 dark:border-gray-700 sm:rounded-lg"},J={key:0,class:"table min-w-full divide-y divide-gray-200 dark:divide-gray-700"},K={class:"bg-gray-50 dark:bg-neutral-700"},Q={class:"px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"},X={class:"px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"},Y={class:"bg-white dark:bg-dark-body divide-y divide-gray-200 dark:divide-gray-700"},Z={class:"text-sm text-gray-500 dark:text-gray-400 pl-6 py-2"},ee={class:"text-sm text-gray-500 dark:text-gray-400 pl-6 py-2"},te={name:"TeamConfigPermissionAssign"},ae=Object.assign(te,{props:{team:{type:Object,default(){return{name:""}}}},setup(h){const d=U(),v=q(),$=I(),g=S(!1),x="team/permission/",u=w({modules:[]}),n={selectedModule:"",assignedPermissions:[]},m=w({...n}),T=e=>{v.push({name:"TeamConfigPermissionAssignModule",params:{module:e}})},B=async()=>{g.value=!0,await $.dispatch(x+"preRequisite",{uuid:d.params.uuid,data:d.params.module||"general"}).then(e=>{g.value=!1,Object.assign(u,{modules:e.modules,selectedModule:e.selectedModule,roles:e.roles}),n.selectedModule=e.selectedModule,n.assignedPermissions=e.assignedPermissions,Object.assign(m,P(n))}).catch(e=>{g.value=!1})};return E(()=>d.params.module,e=>{e&&(n.selectedModule=e,Object.assign(m,P(n)),B())}),z(()=>{B()}),(e,C)=>{const A=o("DropdownItem"),V=o("DropdownButton"),j=o("BaseButton"),D=o("PageHeader"),F=o("CardHeader"),L=o("BaseCheckbox"),O=o("BaseLoader"),R=o("FormAction"),H=o("ParentTransition");return t(),a(c,null,[h.team.uuid?(t(),k(D,{key:0,title:e.$trans(f(d).meta.label),navs:[{label:e.$trans("team.team"),path:"TeamList"},{label:h.team.name,path:{name:"TeamShow",params:{uuid:h.team.uuid}}},{label:e.$trans("team.config.config"),path:"TeamConfig"}]},{default:i(()=>[r("div",W,[u.modules.length?(t(),k(V,{key:0,direction:"down",label:e.$trans("module."+u.selectedModule)},{default:i(()=>[(t(!0),a(c,null,y(u.modules,s=>(t(),a("div",{key:s.value},[s.value!=f(d).params.module?(t(),k(A,{key:0,as:"span",onClick:b=>T(s.value)},{default:i(()=>[M(p(s.label),1)]),_:2},1032,["onClick"])):_("",!0)]))),128))]),_:1},8,["label"])):_("",!0),l(j,{onClick:C[0]||(C[0]=s=>f(v).push({name:"TeamConfigUserPermission"}))},{default:i(()=>[M(p(e.$trans("team.config.permission.user_permission")),1)]),_:1})])]),_:1},8,["title","navs"])):_("",!0),l(H,{appear:"",visibility:!0},{default:i(()=>[l(R,{"no-data-fetch":"","init-url":x,uuid:f(d).params.uuid,action:"roleWiseAssign","init-form":n,form:m,"stay-on":""},{default:i(()=>[l(F,{first:"",title:e.$trans("team.config.permission.permission_config"),description:e.$trans("team.config.permission.permission_info")},null,8,["title","description"]),l(O,{"is-loading":g.value},{default:i(()=>[r("div",G,[m.assignedPermissions.length?(t(),a("table",J,[r("thead",K,[r("tr",null,[r("th",Q,p(e.$trans("team.config.permission.permission")),1),(t(!0),a(c,null,y(u.roles,s=>(t(),a("th",X,p(s.label),1))),256))])]),r("tbody",Y,[(t(!0),a(c,null,y(m.assignedPermissions,s=>(t(),a("tr",{key:s.name},[r("td",Z,p(s.name),1),(t(!0),a(c,null,y(s.roles,b=>(t(),a("td",ee,[l(L,{modelValue:b.isAssigned,"onUpdate:modelValue":N=>b.isAssigned=N},null,8,["modelValue","onUpdate:modelValue"])]))),256))]))),128))])])):_("",!0)])]),_:1},8,["is-loading"])]),_:1},8,["uuid","form"])]),_:1})],64)}}});export{ae as default};